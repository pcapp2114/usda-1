FROM drupal:9-apache

RUN apt update -y && apt upgrade -y && apt-get install -y zip git vim dialog openssh-server
RUN echo "root:Docker!" | chpasswd && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini;
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;

RUN composer self-update
RUN composer global require drush/drush
ENV COMPOSER_ROOT_VERSION 2.7.2
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

RUN mkdir -p /tmp

COPY /.docker/usda-nass-internal/ssh_setup.sh /tmp
COPY /.docker/usda-nass-internal/ssh_setup.sh /root/
COPY /.docker/usda-nass-internal/entrypoint.sh /root/
COPY /.docker/usda-nass-internal/.bashrc /root/
COPY /.docker/usda-nass-internal/.vimrc /root/
COPY /.docker/usda-nass-internal/sshd_config /etc/ssh/
COPY /.docker/usda-nass-internal/000-default.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/

RUN chmod +x /tmp/ssh_setup.sh
RUN chmod +x /root/ssh_setup.sh
RUN chmod +x /root/entrypoint.sh

RUN chown root:root /root/entrypoint.sh
RUN chown root:root /root/.bashrc
RUN chown root:root /root/.vimrc
RUN chown root:root /etc/apache2/sites-available/000-default.conf

RUN /root/ssh_setup.sh && rm /root/ssh_setup.sh && echo "root:Docker!" | chpasswd
RUN (sleep 1; /tmp/ssh_setup.sh 2>&1 > /dev/null) && rm -rf /tmp/*

EXPOSE 80 2222
ENTRYPOINT [ "/entrypoint.sh" ]
