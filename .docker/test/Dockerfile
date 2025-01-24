FROM php:8.3-apache

# Set environment variables for Drupal
ENV APACHE_DOCUMENT_ROOT /var/www/html/web

# Install required dependencies
RUN apt update -y
RUN apt upgrade -y
RUN apt-get install -y zip git vim dialog openssh-server unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zlib1g-dev mariadb-client
RUN apt-get install -y cronie awscli
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd mbstring xml pdo pdo_mysql
RUN a2enmod rewrite
RUN apt-get clean

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ROOT_VERSION 2.8.5

RUN echo "root:Docker!" | chpasswd && rm -rf /var/lib/apt/lists/*

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini;
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;

RUN composer self-update
RUN composer global require drush/drush
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

RUN mkdir -p /tmp

COPY /.docker/test/ssh_setup.sh /tmp
COPY /.docker/test/ssh_setup.sh /root/
COPY /.docker/test/entrypoint.sh /
COPY /.docker/test/.bashrc /root/
COPY /.docker/test/.vimrc /root/
COPY /.docker/test/sshd_config /etc/ssh/
COPY /.docker/test/000-default.conf /etc/apache2/sites-available/
COPY /.docker/test/drupal.conf /etc/apache2/sites-available/
COPY /.docker/test/drupal.conf /etc/apache2/conf-available/
COPY /.docker/test/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/

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

# Set permissions for Drupal
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

COPY /.docker/test/ssh_setup.sh /tmp

EXPOSE 80 2222
ENTRYPOINT [ "/entrypoint.sh" ]
