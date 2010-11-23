#!/bin/sh
#
#  Start Stop Restart script for CrossLight
#  Andrew Niemantsverdriet

#Set the root directory to be where the script is run from
ROOTDIR="`pwd`"

case "$1" in
        start)
          if [ -e $ROOTDIR/app/tmp/lighttpd.pid ]
            then echo "Can not start CrossLight it is already running"
            else
              echo "rootdir = \"$ROOTDIR\"" > $ROOTDIR/etc/lighttpd.conf.local
              $ROOTDIR/bin/lighttpd -f $ROOTDIR/etc/lighttpd.conf -m ./bin/lib/
              echo "CrossLight has been started"
          fi
        ;;

        stop)
          if [ -e $ROOTDIR/app/tmp/lighttpd.pid ]
            then
              kill `cat app/tmp/lighttpd.pid`
              echo "CrossLight has been stopped"
              if [ -e $ROOTDIR/app/tmp/lighttpd.pid ]
                then `rm -f $ROOTDIR/app/tmp/lighttpd.pid`
              fi
            else echo "CrossLight not currently running"
          fi
        ;;

        status)
          if [ -e $ROOTDIR/app/tmp/lighttpd.pid ]
            then echo "CrossLight is running"
            else echo "CrossLight is stopped"
          fi
        ;;

         debug)
          if [ -e $ROOTDIR/app/tmp/lighttpd.pid ]
            then echo "CrossLight is running, stop first before running in debug mode"
            else
              echo "rootdir = \"$ROOTDIR\"" > $ROOTDIR/etc/lighttpd.conf.local
              $ROOTDIR/bin/lighttpd -D -f $ROOTDIR/etc/lighttpd.conf -m ./bin/lib/
          fi
        ;;

        restart)
          $ROOTDIR/crosslight.sh stop
          $ROOTDIR/crosslight.sh start
        ;;

        *)
          echo $"Usage: $0 {start|stop|restart|status|debug}"
          exit 1
esac
