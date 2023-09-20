FROM usdadaito/ewaps:apache47php81

ARG DOCROOT=/var/application/web

# Install AWS CLI and cron
RUN yum update -y
RUN yum install -y cronie awscli
RUN yum clean all

# Copy and execute the script to sync files from S3
COPY setEnv.sh /home/
COPY s3_sync.sh /home/

RUN echo "0 5 * * * /home/s3_sync.sh >> /home/cron.log 2>&1" > /home/cron.txt
RUN crontab /home/cron.txt
RUN chmod 777 /home

RUN mkdir -p "$DOCROOT/sites/default"; ln -s "/mnt/public-content" "$DOCROOT/sites/default/files"
COPY settings.php $DOCROOT/sites/default/

RUN sed -i '2i /home/setEnv.sh' /run-httpd.sh
