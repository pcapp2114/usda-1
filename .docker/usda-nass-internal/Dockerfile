FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/web

RUN apt-get -y update
RUN apt-get -y upgrade
RUN apt-get install -y  \
    zip git vim dialog openssh-server unzip libpng-dev  \
    libjpeg-dev libpng-dev libwebp-dev libfreetype6-dev  \
    libonig-dev libxml2-dev zlib1g-dev  \
    mariadb-client awscli python3 python3-pip  \
    apt-transport-https ca-certificates curl gnupg lsb-release cron

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) gd mbstring xml pdo pdo_mysql intl opcache

#RUN docker-php-ext-configure brotli --with-libbrotli
#RUN docker-php-ext-install "-j$(nproc)" brotli
#RUN apt-get install -y -qq php-imagick
#RUN apt-get install -y -qq php-common
#RUN apt-get install -y -qq php-cli
#RUN apt-get install -y -qq pv mailutils build-essential make gcc net-tools  \
#    software-properties-common libgsasl7 gsasl
#    curl \
#    wget \
#    vim \
#    git \
#    unzip \
#    mysql-client

RUN a2enmod rewrite
RUN apt-get -y clean
RUN apt-get -y autoclean
RUN apt-get -y autoremove

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ROOT_VERSION 2.8.5

RUN echo "root:Docker!" | chpasswd && rm -rf /var/lib/apt/lists/*

RUN echo 'memory_limit = 4096M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini;
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;
RUN echo 'session.cookie_samesite = Lax' >> /usr/local/etc/php/conf.d/docker-php-session_cookie_samesite.ini;

RUN composer self-update
RUN composer global require drush/drush
RUN composer global update
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

RUN mkdir -p /tmp

COPY /.docker/usda-nass-internal/ssh_setup.sh /tmp
COPY /.docker/usda-nass-internal/ssh_setup.sh /root/
COPY /.docker/usda-nass-internal/entrypoint.sh /
COPY /.docker/usda-nass-internal/.bashrc /root/
COPY /.docker/usda-nass-internal/.vimrc /root/
COPY /.docker/usda-nass-internal/sshd_config /etc/ssh/
COPY /.docker/usda-nass-internal/000-default.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/sites-available/
COPY /.docker/usda-nass-internal/drupal.conf /etc/apache2/conf-available/
COPY /.docker/usda-nass-internal/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/

RUN chmod -R +x /tmp/ssh_setup.sh
RUN chmod -R +x /root/ssh_setup.sh
RUN chmod -R +x /entrypoint.sh

RUN chown root:root /entrypoint.sh
RUN chown root:root /root/.bashrc
RUN chown root:root /root/.vimrc
RUN chown root:root /etc/apache2/sites-available/000-default.conf
RUN chown root:root /etc/apache2/sites-available/drupal.conf
RUN chown root:root /etc/apache2/conf-available/drupal.conf

RUN /root/ssh_setup.sh && rm /root/ssh_setup.sh && echo "root:Docker!" | chpasswd
RUN (sleep 1; /tmp/ssh_setup.sh 2>&1 > /dev/null) && rm -rf /tmp/*

RUN a2enconf drupal

WORKDIR /var/www/html

COPY [ "composer.json", "composer.lock", ".mounts", "./" ]
COPY config config/
COPY web web/

COPY /.docker/usda-nass-internal/settings.php /var/www/html/web/sites/default/
COPY /.docker/usda-nass-internal/development-services.yml /var/www/html/web/sites/default/
RUN ln -s /mnt/public-content /var/www/html/web/sites/default/files
RUN ln -s /mnt/private-content /var/www/html/private
#RUN "* * * * * wget -q -O /dev/null \"http://internaldrupaldev.nass.usda.gov/cron/A2ygWfkVpqGLIpf3hob1BXABU1YDJgjuZyJW4vJ4iEApUAZQZEJX9KfgfN4uRNd6FxXhuEc8lg\"">>setcron

RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN chmod -R 444 /var/www/html/web/sites/default/settings.php
RUN chmod -R 444 /var/www/html/web/sites/default/services.yml

RUN composer install
RUN ln -s /var/www/html/vendor/drush/drush/ /usr/local/bin/drush
#RUN chown root:root /usr/local/bin/drush
#RUN drush cr

WORKDIR /root

EXPOSE 80 2222
ENTRYPOINT [ "/entrypoint.sh" ]
