#!/usr/bin/env bash
set -e

echo "Starting entrypoint.sh..."

echo "Starting SSH..."
/usr/sbin/sshd
echo "Finished starting SSH..."

printenv | grep DOCROOT > /home/.env
printenv | grep AWS >> /home/.env
sed -e 's/^/export /' -i /home/.env

crond -n &
