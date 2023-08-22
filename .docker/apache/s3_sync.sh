#!/bin/bash

# Sync files from S3 bucket to the local directory
aws s3 sync s3://$S3_BUCKET_NAME $LOCAL_DIR

echo "S3 synchronization complete"

# Restart Apache after synchronization (if needed)
apachectl restart