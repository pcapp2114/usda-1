#!/bin/bash

. /home/.env

FILE="${DOCROOT}/sites/default/files"

# Sync files from local directory to S3 bucket
aws s3 sync "s3://${AWS_S3_BUCKET}" "$FILE" --no-progress >> /home/cron.log 2>&1

echo "S3 Sync completed at $(date)" >> /home/cron.log 2>&1
