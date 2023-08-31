#!/bin/bash

source /home/setEnv.sh

FILE="/var/application/web/sites/default/files"

# Sync files from local directory to S3 bucket
aws s3 sync "$FILE" s3://webmod-files-sync --no-progress >> /var/log/apache_cron.log 2>&1 

echo "S3 Sync completed at $(date)" >> /var/log/apache_cron.log 2>&1 
