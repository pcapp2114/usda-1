FROM mariadb:10.6

# install cron
RUN apt update
RUN apt install -y cron awscli

# set up our cron job - runs every Sunday at 11:59pm
COPY setEnv.sh /home/
COPY export.sh /home/
# TODO - 0 0 * * 0 - correct schedule
RUN echo "* * * * * /home/export.sh >> /home/cron.log 2>&1" > /home/cron.txt
RUN crontab /home/cron.txt

ENTRYPOINT [ "docker-entrypoint.sh" ]
#ENTRYPOINT [ "/home/setEnv.sh" ]
#EXPOSE 3306
CMD ["mariadbd"]
