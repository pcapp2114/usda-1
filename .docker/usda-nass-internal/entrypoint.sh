#!/usr/bin/env bash
set -e

# Get environment variables to show up in SSH session
eval $(printenv | awk -F= '{print "export " "\""$1"\"""=""\""$2"\"" }' >> /etc/profile)

echo "Starting entrypoint.sh..."

echo "Starting SSH..."
/usr/sbin/sshd
echo "Finished starting SSH..."

/usr/local/bin/apache2-foreground
