#!/usr/bin/env bash
set -e

# Get environment variables to show up in SSH session
printenv | awk -F= '{print "export " "\"" $1 "\"" "=" "\"" $2 "\"" }' >> /etc/profile

echo "Starting entrypoint.sh..."

echo "Starting cron..."
service cron start
echo "Finished starting cron..."

echo "Starting SSH..."
/usr/sbin/sshd
echo "Finished starting SSH..."

echo "Starting Apache..."
exec /usr/local/bin/apache2-foreground
