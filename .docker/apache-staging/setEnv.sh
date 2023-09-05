#!/usr/bin/env bash

printenv | grep AWS >> /home/.env
sed -e 's/^/export /' -i /home/.env

crond -n &
