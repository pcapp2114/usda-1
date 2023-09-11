#!/usr/bin/env bash

printenv | grep MYSQL > /home/.env
printenv | grep AWS >> /home/.env
sed -e 's/^/export /' -i /home/.env

cron -n
