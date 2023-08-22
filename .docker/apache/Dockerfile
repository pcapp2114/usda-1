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

# Copy and execute the script to sync files from S3
COPY s3_sync.sh /home/s3_sync.sh
RUN chmod +x /home/s3_sync.sh

# Create cron job that runs S3 sync every Sunday at 11:59pm
RUN echo "59 23 * * 0 /home/s3_sync.sh" | crontab -
