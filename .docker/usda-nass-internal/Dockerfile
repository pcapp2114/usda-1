FROM php:8.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/web

#Needs Review : brotli imagick mysql-client

### Preparing the server and all the libraries ###
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y  \
    make gcc wget zip git vim dialog openssh-server unzip libpng-dev  \
    pv mailutils libjpeg-dev libpng-dev libwebp-dev libfreetype6-dev  \
    net-tools build-essential libonig-dev libxml2-dev zlib1g-dev  \
    mariadb-client awscli python3 python3-pip  \
    apt-transport-https ca-certificates curl gnupg lsb-release cron
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/

RUN set -eux; docker-php-ext-install -j1 pdo pdo_mysql opcache
RUN set -eux; docker-php-ext-install -j1 xml mbstring
# RUN set -eux; docker-php-ext-install -j1 intl
RUN set -eux; docker-php-ext-install -j1 gd

# RUN docker-php-ext-install -j$(nproc) gd mbstring xml pdo pdo_mysql intl opcache
RUN a2enmod rewrite
RUN apt-get -y clean
RUN apt-get -y autoclean
RUN apt-get -y autoremove
RUN echo "root:Docker!" | chpasswd && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /tmp

### Composer installation and configuration ###
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ROOT_VERSION 2.8.6
RUN composer self-update
RUN composer global require drush/drush
RUN composer global update
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

### PHP.ini configurations ###
RUN echo 'memory_limit = 4096M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini;
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;
RUN echo 'session.cookie_samesite = Lax' >> /usr/local/etc/php/conf.d/docker-php-session_cookie_samesite.ini;

### Copying/Configuring/Permissions all needed files and scripts ###
COPY /.docker/usda-nass-internal/ssh_setup.sh /tmp
COPY /.docker/usda-nass-internal/ssh_setup.sh /root/
COPY /.docker/usda-nass-internal/entrypoint.sh /
COPY /.docker/usda-nass-internal/.bashrc /root/
COPY /.docker/usda-nass-internal/.vimrc /root/
COPY /.docker/usda-nass-internal/cron.sh /root/
COPY /.docker/usda-nass-internal/sshd_config /etc/ssh/
COPY /.docker/usda-nass-internal/000-default.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/conf-available/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.cer /etc/ssl/certs/

RUN chmod -R +x /tmp/ssh_setup.sh
RUN chmod -R +x /root/ssh_setup.sh
RUN chmod -R +x /root/cron.sh
RUN chmod -R +x /entrypoint.sh

RUN chown root:root /entrypoint.sh
RUN chown root:root /root/.bashrc
RUN chown root:root /root/.vimrc
RUN chown root:root /etc/apache2/sites-available/000-default.conf
RUN chown root:root /etc/apache2/sites-available/drupal.conf

### Setting up SSH ###
RUN /root/ssh_setup.sh && rm /root/ssh_setup.sh && echo "root:Docker!" | chpasswd
RUN (sleep 1; /tmp/ssh_setup.sh 2>&1 > /dev/null) && rm -rf /tmp/*
RUN a2enconf drupal

### Setting up the Drupal Site ###
WORKDIR /var/www/html

COPY [ "composer.json", "composer.lock", "./" ]
COPY config config/
COPY web web/
COPY /.docker/usda-nass-internal/settings.php /var/www/html/web/sites/default/
COPY /.docker/usda-nass-internal/development-services.yml /var/www/html/web/sites/default/

RUN ln -s /mnt/public-content /var/www/html/web/sites/default/files
RUN ln -s /mnt/private-content /var/www/html/private

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN chmod -R 444 /var/www/html/web/sites/default/settings.php
RUN chmod -R 444 /var/www/html/web/sites/default/services.yml

RUN composer install
RUN /root/cron.sh && rm /root/cron.sh && echo "root:Docker!" | chpasswd
RUN service cron start

WORKDIR /root

EXPOSE 80 2222
ENTRYPOINT [ "/entrypoint.sh" ]
