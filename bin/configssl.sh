#!/bin/sh

echo "Run this from the crosslight root directory.";

if [ ! -z etc/ssl/ ] ; then mkdir --parents etc/ssl ; fi

cd etc/ssl
openssl genrsa -des3 -out crosslight.key 1024  #Build our key
openssl req -new -key crosslight.key -out crosslight.csr #Build the cert request
cp crosslight.key{,.orig} 
openssl rsa -in crosslight.key.orig -out crosslight.key #Ignore the passphrase
openssl x509 -req -days 365 -in crosslight.csr -signkey crosslight.key -out crosslight.crt #Build the cert
cat crosslight.key crosslight.crt > crosslight.pem #Build the pem
cd ../..
echo "include \"lighttpd.ssl.conf\"" >> etc/lighttpd.conf

echo "After running this script, you will need to use https://localhost:3000/ unless you change your configuration."
