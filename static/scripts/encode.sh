#!/bin/sh

/usr/bin/openssl aes-128-cbc -a -salt -k $1 -in $2 
