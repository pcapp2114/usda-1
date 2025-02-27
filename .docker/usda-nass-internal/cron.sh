#!/bin/bash
# Set Crontabs

{
  echo "* * * * * wget -q -O /dev/null http://internaldrupaldev.nass.usda.gov/cron/A2ygWfkVpqGLIpf3hob1BXABU1YDJgjuZyJW4vJ4iEApUAZQZEJX9KfgfN4uRNd6FxXhuEc8lg"
} >>setcron

crontab setcron
rm setcron

#cd /var/www/html || exit
#drush cr
#drush updb -y
#drush cim -y
