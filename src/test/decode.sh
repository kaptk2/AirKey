#!/bin/sh
/usr/bin/openssl aes-128-cbc -a -d -salt -k $1 -in $2
