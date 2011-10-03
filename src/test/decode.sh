#!/bin/sh
key=`echo -n $1 | openssl md5 | awk '{ print $2 }'`
/usr/bin/openssl aes-256-cbc -a -d -salt -k $key -in $2
