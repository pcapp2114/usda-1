FROM usdadaito/ewaps:apache47php81

ARG DOCROOT=/var/application/web

# Install AWS CLI and cron
RUN yum update -y
RUN yum install -y cronie awscli
RUN yum clean all

# Copy and execute the script to sync files from S3
COPY ./setEnv.sh /home/setEnv.sh
COPY ./s3_sync.sh /home/s3_sync.sh

RUN chmod -R +x /home/setEnv.sh \
   && chmod -R +x /home/s3_sync.sh

RUN echo "0 5 * * * /home/s3_sync.sh >> /home/cron.log 2>&1" > /home/cron.txt
# RUN crontab /home/cron.txt      commenting out as we don't want to pull in stuff from QA
RUN chmod 777 /home

RUN sed -i '2i /home/setEnv.sh' /run-httpd.sh
