#!/usr/bin/env bash

printenv | grep DOCROOT > /home/.env
printenv | grep AWS >> /home/.env
sed -e 's/^/export /' -i /home/.env

crond -n &
