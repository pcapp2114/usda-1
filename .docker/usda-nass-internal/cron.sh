#!/bin/bash
# Set Crontabs

rand=$(($RANDOM % 23))

{
  echo "@reboot /home/ubuntu/scripts/random_cron"
  echo "* * * * * /home/ubuntu/scripts/restart_apache"
  echo "0 $rand * * * /home/ubuntu/scripts/update_server"
} >>setcron

crontab setcron
rm setcron
