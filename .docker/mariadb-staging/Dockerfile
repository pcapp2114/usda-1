FROM mariadb:10.6

# install cron
RUN apt update
RUN apt install -y cron awscli

# set up our cron job - runs every Sunday at 11:59pm
COPY setEnv.sh /home/
COPY export.sh /home/

RUN echo "0 5 * * * /home/export.sh >> /home/cron.log 2>&1" > /home/cron.txt
RUN crontab /home/cron.txt
RUN chmod 777 /home
RUN sed -i '2i /home/setEnv.sh' /usr/local/bin/docker-entrypoint.sh
