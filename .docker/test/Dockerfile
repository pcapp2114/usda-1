FROM usdadaito/ewaps:apache47php81

ARG DOCROOT=/var/application/web

# Install AWS CLI and cron
#RUN yum update -y \
#    && yum update-minimal -y --security --sec-severity=Important --sec-severity=Critical \
#    && yum install -y cronie awscli \
#    && yum clean all

#RUN apt update -y && apt upgrade -y && apt-get install -y zip git vim dialog openssh-server cronie awscli

# Required for Azure SSH
# See: https://learn.microsoft.com/en-us/azure/app-service/configure-custom-container?pivots=container-linux&tabs=debian#enable-ssh
# Copy the sshd_config file to the /etc/ssh/ directory
COPY /.docker/test/ssh_setup.sh /home/
RUN chmod +x /home/ssh_setup.sh
COPY /.docker/test/sshd_config /etc/ssh/
# configure the ssh_setup file and setup the permissions
RUN /home/ssh_setup.sh && rm /home/ssh_setup.sh && echo "root:Docker!" | chpasswd

# Copy and configure the ssh_setup file
RUN mkdir -p /tmp
COPY /.docker/test/ssh_setup.sh /tmp
RUN chmod -R +x /tmp/ssh_setup.sh \
   && (sleep 1;/tmp/ssh_setup.sh 2>&1 > /dev/null) \
   && rm -rf /tmp/*

# Copy and execute the script to sync files from S3
COPY /.docker/test/setEnv.sh /home/
COPY /.docker/test/s3_sync.sh /home/
COPY /.docker/test/entrypoint.sh /home/

RUN chmod -R +x /home/setEnv.sh \
   && chmod -R +x /home/s3_sync.sh \
   && chmod -R +x /home/entrypoint.sh \

RUN chown root:root /home/entrypoint.sh

RUN echo "0 5 * * * /home/s3_sync.sh >> /home/cron.log 2>&1" > /home/cron.txt
# RUN crontab /home/cron.txt      commenting out as we don't want to pull in stuff from QA
RUN chmod 777 /home

EXPOSE 80 2222

RUN sed -i '2i /home/setEnv.sh' /run-httpd.sh
ENTRYPOINT [ "/home/entrypoint.sh" ]
