FROM mariadb:10.6

# passed in credentials
ARG MYSQL_DATABASE
ENV MYSQL_DATABASE ${MYSQL_DATABASE}
ARG MYSQL_PASSWORD

# install cron
RUN apt update
RUN apt install -y cron

# set up our cron job - runs every Sunday at 11:59pm
COPY export.sh /home/
# TODO - 0 0 * * 0 - correct schedule
RUN eval $(printenv | awk -F= '{print "export " "\""$1"\"""=""\""$2"\"" }' >> /etc/profile) && \
   echo "* * * * * BASH_ENV=/etc/profile /home/export.sh" > /home/cron.txt
RUN crontab /home/cron.txt && crond -n &
