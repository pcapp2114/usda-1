#!/bin/sh

cd /etc/ssh/

# Generate all SSH keys (note: Azure only supports RSA)
# https://learn.microsoft.com/en-us/azure/virtual-machines/linux/mac-create-ssh-keys
ssh-keygen -A

#prepare run dir
if [ ! -d "/var/run/sshd" ]; then
    mkdir -p /var/run/sshd
fi
