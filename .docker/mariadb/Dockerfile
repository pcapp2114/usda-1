FROM mariadb:10.6

# passed in credentials
ARG MYSQL_DATABASE
ENV MYSQL_DATABASE $MYSQL_DATABASE
ARG MYSQL_PASSWORD

# install cron
RUN apt update
RUN apt install -y cron curl

# set up our cron job - runs every Sunday at 11:59pm
#RUN chmod +r /proc/1/environ
COPY setEnv.sh /home/
COPY export.sh /home/
# TODO - 0 0 * * 0 - correct schedule
RUN echo "* * * * * /home/export.sh" > /home/cron.txt
RUN crontab /home/cron.txt
RUN cron -n &
