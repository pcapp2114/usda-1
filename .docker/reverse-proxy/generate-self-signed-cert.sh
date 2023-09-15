#!/bin/sh
# generate self signed ssl cert only if all cert files are empty or nonexistent

#if [ -s /etc/ssl/ssl.crt ] || [ -s /etc/ssl/cert.pem ] || [ -s /etc/ssl/key.pem ] || [ -n "${SKIP_SSL_GENERATE}" ]; then
mkdir -p /etc/ssl
cd /etc/ssl

openssl genrsa -des3 -passout pass:x -out localhost.key 4096

# Note: the -dsaparam speeds this process up A LOT
openssl dhparam -dsaparam -out /etc/nginx/dhparam.pem 4096

cp localhost.key localhost.key.orig
openssl rsa -passin pass:x -in localhost.key.orig -out localhost.key

mkdir -p /etc/nginx/private
cp localhost.key /etc/nginx/private/localhost.key

openssl req -key localhost.key -new -out localhost.csr -subj "/C=US/L=Washington D.C./O=United States Department of Agriculture/OU=Digital/CN=localhost"

openssl x509 -signkey localhost.key -in localhost.csr -req -days 365 -out /etc/nginx/localhost.crt
