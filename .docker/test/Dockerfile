FROM php:8.3-apache

# Set environment variables for Drupal
ENV APACHE_DOCUMENT_ROOT /var/www/html/web

# Install required dependencies
RUN apt update -y
RUN apt upgrade -y
RUN apt-get install -y zip
RUN apt-get install -y git
RUN apt-get install -y vim
RUN apt-get install -y dialog
RUN apt-get install -y openssh-server
RUN apt-get install -y unzip
RUN apt-get install -y libpng-dev
RUN apt-get install -y libjpeg-dev
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y libonig-dev
RUN apt-get install -y libxml2-dev
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y mariadb-client
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) xml
RUN docker-php-ext-install -j$(nproc) pdo
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN a2enmod rewrite
RUN apt-get clean

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ROOT_VERSION 2.8.5

RUN echo "root:Docker!" | chpasswd && rm -rf /var/lib/apt/lists/*

RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;
RUN echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini;
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;

#RUN composer self-update
#RUN composer global require drush/drush
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

RUN mkdir -p /tmp

COPY /.docker/test/ssh_setup.sh /tmp
COPY /.docker/test/ssh_setup.sh /root/
COPY /.docker/test/entrypoint.sh /
COPY /.docker/test/.bashrc /root/
COPY /.docker/test/.vimrc /root/
COPY /.docker/test/sshd_config /etc/ssh/
COPY /.docker/test/000-default.conf /etc/apache2/sites-available/
COPY /.docker/test/BaltimoreCyberTrustRoot.crt.pem /etc/ssl/certs/

RUN chmod -R +x /tmp/ssh_setup.sh
RUN chmod -R +x /root/ssh_setup.sh
RUN chmod -R +x /entrypoint.sh

RUN chown root:root /entrypoint.sh
RUN chown root:root /root/.bashrc
RUN chown root:root /root/.vimrc
RUN chown root:root /etc/apache2/sites-available/000-default.conf

RUN /root/ssh_setup.sh && rm /root/ssh_setup.sh && echo "root:Docker!" | chpasswd
RUN (sleep 1; /tmp/ssh_setup.sh 2>&1 > /dev/null) && rm -rf /tmp/*

# Set permissions for Drupal
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable Apache mod_rewrite for Drupal clean URLs
RUN echo "<Directory /var/www/html/web>\n\
    AllowOverride All\n\
    </Directory>" > /etc/apache2/conf-available/drupal.conf \
    && a2enconf drupal

EXPOSE 80 2222
ENTRYPOINT [ "/entrypoint.sh" ]
