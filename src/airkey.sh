#!/bin/sh
#
#  Initalize and setup AP using AirKey controller
#  Andrew Niemantsverdriet
#

# The URL of the airKey controller
# Change this to fit your install
CONTROLLER="http://andrew.rimrockhosting.com" #No trailing slash

# Get Current Directory
DIRECTORY="/etc/airkey" #No trailing slash

# Posted Variables
local_uptime=`cut -f1 -d' ' /proc/uptime`

case "$1" in
        init)
          # Create the configuration file
          if [ ! -e "$DIRECTORY/config.txt" ]
            then
              # MAC address
              echo "var_mac=`/sbin/ifconfig eth0 | grep "HWaddr" | awk '{ print $5 }' | sed "s/://g"`" > $DIRECTORY/config.txt
              echo -n "var_key='" >> $DIRECTORY/config.txt
              tr -dc A-Za-z0-9 < /dev/urandom | head -c 32 >> $DIRECTORY/config.txt
              echo "'" >> $DIRECTORY/config.txt
              echo "var_version='0'" >> $DIRECTORY/config.txt
            else echo "The file: $DIRECTORY/config.txt already exists please remove and run init again"
          fi
          # Create a Network Key if needed
          if [ ! -e "$DIRECTORY/network.key" ]
            then
              echo -n "Please Enter your network key: "
              read network_key
              echo $network_key >> $DIRECTORY/network.key
          fi
        ;;

        force)
          if [ -e "$DIRECTORY/config.txt" ]
            then
              sed "3s/.*/var_version='0'/" $DIRECTORY/config.txt > /tmp/config.temp
              mv -f /tmp/config.temp $DIRECTORY/config.txt
              $DIRECTORY/airkey.sh run
            else
              $DIRECTORY/airkey.sh init
              $DIRECTORY/airkey.sh run
          fi
        ;;

        run)
          if [ -e "$DIRECTORY/config.txt" ]
            then
              source $DIRECTORY/config.txt
              curl -k -L -s -o /tmp/encrypted -d "uptime=$local_uptime&ap_version=$var_version" $CONTROLLER/register/auth/$var_mac/$var_key/$var_version
              openssl aes-128-cbc -a -d -salt -kfile $DIRECTORY/network.key -in /tmp/encrypted -out /tmp/checkin
              rm -f /tmp/encrypted
              source /tmp/checkin
              # Run any commands if listed
              if [ -n "$var_run" ]
                then
                  $var_run
                  curl -k -L -s $CONTROLLER/register/auth/$var_mac/$var_key/removeCommand
              fi
              # Check Version and decide what to do next
              if [ $var_version \< $var_server_version ]
                then # Newer version found
                # Parse the modules
                for module in $var_modules #Array of modules
                do
                  # Get module config file
                  curl -k -s -o /tmp/$module $CONTROLLER/modules/$module/auth/$var_mac/$var_key
                  source /tmp/$module # Use the variables provided in the module defination file

                  if [ -n "$var_package" ] #If the package variable exists make sure it is installed
                    then
                      /bin/opkg list-installed |awk '{print $1}' > /tmp/installed # Build list of pkgs
                      if grep -q -E '^'$var_package'$' /tmp/installed # Search for the package name
                        then
                          continue # Package already installed move on
                        else
                          /bin/opkg update # Package not installed, so install it
                          /bin/opkg install $var_package
                    fi

                    if [ -e "/tmp/installed" ]
                      then rm -f /tmp/installed # Clean up installed package list
                    fi
                  fi

                  if [ -n "$var_remote_files" ] # If the files variable exists get all files
                    i=1
                    then
                      for file in $var_remote_files # Array of files to get off of remote server
                      do
                      # Get files
                      location="`echo $var_local_files | awk -v i=$i '{print $i}'`" # Array of where files go on local FS
                      # wget file from login password secured location
                      wget --user=`echo $var_mac | sed s/://g` --password=$var_key -O $location $CONTROLLER/static/modules/$module/$file
                      i=`expr $i + 1`
                      done
                  fi

                  if [ -n "$var_command" ] #If there are commands run then run them
                    then $var_command
                  fi
                  rm /tmp/$module # Cleanup the module file
                done

                # Update the version number in the config file
                sed "3s/.*/var_version='$var_server_version'/" $DIRECTORY/config.txt > /tmp/config.temp
                mv -f /tmp/config.temp $DIRECTORY/config.txt
              fi
            else
              echo "config not found initalizing new one."
              $DIRECTORY/airkey.sh init
          fi
        ;;

        *)
          echo "Usage: $0 {init|force|run}"
          exit 1
esac
