FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/web

# Needs Review : brotli imagick mysql-client

### Preparing the server and all the libraries ###
RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y  \
    make gcc wget zip git vim dialog openssh-server unzip \
    pv mailutils pkg-config \
    libjpeg62-turbo-dev libpng-dev libwebp-dev libfreetype6-dev \
    net-tools build-essential libonig-dev libxml2-dev zlib1g-dev  \
    mariadb-client awscli python3 python3-pip  \
    apt-transport-https ca-certificates curl gnupg lsb-release cron

# Build GD with JPEG + WebP + FreeType support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp

RUN set -eux; docker-php-ext-install -j"$(nproc)" pdo pdo_mysql opcache
RUN set -eux; docker-php-ext-install -j"$(nproc)" xml mbstring
# RUN set -eux; docker-php-ext-install -j"$(nproc)" intl
RUN set -eux; docker-php-ext-install -j"$(nproc)" gd

# RUN docker-php-ext-install -j$(nproc) gd mbstring xml pdo pdo_mysql intl opcache
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN apt-get -y clean
RUN apt-get -y autoclean
RUN apt-get -y autoremove
RUN echo "root:Docker!" | chpasswd && rm -rf /var/lib/apt/lists/*
RUN mkdir -p /tmp
RUN mkdir -p /root/scripts

### Composer installation and configuration ###
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ROOT_VERSION=2.8.6
RUN composer self-update
RUN composer global require drush/drush
RUN composer global update
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

### PHP.ini configurations ###
RUN echo 'memory_limit = 4096M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini
RUN echo 'session.cookie_samesite = Lax' >> /usr/local/etc/php/conf.d/docker-php-session_cookie_samesite.ini

### Production PHP log/error configuration ###
RUN { \
  echo 'display_errors = Off'; \
  echo 'display_startup_errors = Off'; \
  echo 'log_errors = On'; \
  echo 'error_reporting = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED'; \
  echo 'zend.assertions = -1'; \
  echo 'assert.exception = 0'; \
} > /usr/local/etc/php/conf.d/zz-production.ini

### Copying/Configuring/Permissions all needed files and scripts ###
COPY /.docker/usda-nass-internal/ssh_setup.sh /tmp
COPY /.docker/usda-nass-internal/ssh_setup.sh /root/
COPY /.docker/usda-nass-internal/entrypoint.sh /
COPY /.docker/usda-nass-internal/.bashrc /root/
COPY /.docker/usda-nass-internal/.vimrc /root/
COPY /.docker/usda-nass-internal/cron.sh /root/
COPY /.docker/usda-nass-internal/dbdump.sh /root/scripts/
COPY /.docker/usda-nass-internal/sshd_config /etc/ssh/
COPY /.docker/usda-nass-internal/000-default.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/conf-available/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.crt /etc/ssl/certs/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.cer /etc/ssl/certs/

RUN chmod -R +x /tmp/ssh_setup.sh
RUN chmod -R +x /root/ssh_setup.sh
RUN chmod -R +x /root/cron.sh
RUN chmod -R +x /root/scripts/dbdump.sh
RUN chmod -R +x /entrypoint.sh

RUN chown root:root /entrypoint.sh
RUN chown root:root /root/scripts/dbdump.sh
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

### Drupal cron job and database dump cron job ###
RUN { \
    echo 'SHELL=/bin/bash'; \
    echo 'PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'; \
    echo ''; \
    echo '* * * * * root cd /var/www/html && /var/www/html/vendor/bin/drush cron -y >> /var/log/drupal-cron.log 2>&1'; \
    echo '0 0 * * * root /bin/bash /root/scripts/dbdump.sh >> /var/log/dbdump-cron.log 2>&1'; \
    echo ''; \
  } > /etc/cron.d/nass-cron \
    && chmod 0644 /etc/cron.d/nass-cron \
    && chown root:root /etc/cron.d/nass-cron

WORKDIR /root

EXPOSE 80 2222

ENTRYPOINT [ "/entrypoint.sh" ]
