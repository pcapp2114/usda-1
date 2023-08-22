# Use Apache base image
FROM httpd:latest

# Install AWS CLI and cron
RUN apt-get update && \
    apt-get install -y \
    awscli \
    cron
    
# Set AWS credentials
ARG AWS_ACCESS_KEY_ID
ARG AWS_SECRET_ACCESS_KEY
ENV AWS_ACCESS_KEY_ID=$AWS_ACCESS_KEY_ID
ENV AWS_SECRET_ACCESS_KEY=$AWS_SECRET_ACCESS_KEY

# Set the S3 bucket and Drupal directory paths
ARG S3_BUCKET_NAME
ENV S3_BUCKET_NAME=webmod-files-sync
ENV LOCAL_DIR=/var/application/web/sites/default/files

# Copy the Apache configuration file
COPY apache.conf /usr/local/apache2/conf/httpd.conf

# Copy and execute the script to sync files from S3
COPY sync_s3.sh /usr/local/bin/s3_sync.sh
RUN chmod +x /usr/local/bin/s3_sync.sh
CMD ["s3_sync.sh"]
