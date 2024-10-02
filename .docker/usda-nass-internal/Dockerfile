FROM drupal:9-apache

#Change working directory so files can be copied to correct path
#WORKDIR /
#NASS CUSTOM SSH
RUN apt-get update && apt-get install -y openssh-server
#is used to set up a directory /var/run/sshd inside the container.
# The SSH daemon requires this directory to function.

RUN mkdir /var/run/sshd

#Sets the root password in the container
RUN echo 'root:nass@only!!123' | chpasswd

#changes the sshd_config file to enable root password login, which means allowing the "root" user to log in using a password instead of relying on other authentication methods like SSH keys.
RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

#modifies the Pluggable Authentication Module configuration for the SSH daemon to prevent possible issues with systemd.
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

EXPOSE 22

#Copy necessary SSH Files (Not needed for local but REQUIRED when pushing to Azure Container)
#disabled COPY custom-distro/ssh_files/sshd_config /etc/ssh/
# COPY custom-distro/ssh_files/entrypoint.sh ./#Not needed with latest image. Code copied to docker-php-entrypoint
#Copy certificates for Connection to MySQL Database in Azure
#COPY custom-distro/ssh_files/DigiCertGlobalRootCA.crt.pem /var/www/html/certs/

#Below is a copy of the docker-php-entrypoint file used in drupal image.  This copied locally and modified to add SSH.  This should only be copied using image in Azure
#COPY custom-distro/ssh_files/docker-php-entrypoint ./usr/local/bin/
#Copy database backup and restore scripts to image (WHEN NECESSARY)
#COPY custom-distro/db-backup /root/db-backup

# Install system packages BEFORE application code to reduce build time and unnecessary repeat installations
# Install apt-get, a command line interface for retrieval of packages
# Install zip, git, vim, dialog, openssh-server
# Start and enable SSH to Use On Azure ONLY (Won't have immediate effects on localhost but can cause issue when trying to do sqldump)
# Clean up package install cache at the end
RUN apt update \
    && apt upgrade -y \
    && apt-get install -y \
        zip \
        git \
        vim \
        dialog \
        openssh-server \
    && echo "root:Docker!" | chpasswd \
    #&& chmod u+x ./usr/local/bin/docker-php-entrypoint \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*
    #Install MySQL for ease of use of backing up and restoring DB (203MB so don't install on Azure Image)
    #&& apt install mariadb-server mariadb-client-core -y

#change default memory_limit from 128M to 256M - Fatal error: Allowed memory size of 134217728 bytes exhausted (tried to allocate 987136 bytes) in...
RUN echo 'memory_limit = 2048M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini; \
    #change max_input_var to prevent Warning: PHP Request Startup: Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini.
    echo 'max_input_vars = 3000' >> /usr/local/etc/php/conf.d/docker-php-max_input_vars.ini; \
    # Enable output buffering
    echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output_buffering.ini;

RUN composer self-update
ENV COMPOSER_ROOT_VERSION 2.7.2 
ENV BUILDX_NO_DEFAULT_ATTESTATIONS=1

#Install dependencies: curl, git, unzip, and install coposer
#RUN apt-get update &&

#Install Drush globally using Composer
RUN composer global require drush/drush

# Copy composer files and install before the source code is copied
# This shortens build times when doing code changes but not dependency changes
# COPY usda-ers-drupal-code/composer.json usda-ers-drupal-code/composer.lock /opt/drupal/
# COPY usda-ers-drupal-code/Patches/ /opt/drupal/Patches
# WORKDIR /opt/drupal
# RUN composer install
# WORKDIR /
#WORKDIR /opt/drupal

#COPY code to image
# Try this absolute instead..
# Try /opt/drupal/web
COPY web/ /opt/drupal/web/
#COPY ../../../web /opt/drupal/
RUN chown 755 -R /opt/drupal/web/sites/default/files \
#   && chmod 755 /opt/drupal/config-sync \
    && chmod 755 -R /opt/drupal/web/profiles \
    && chmod 755 -R /opt/drupal/web/modules \
    && chmod 755 -R /opt/drupal/web/themes \
    && chmod 755 -R /opt/drupal/web/sites/default \
    && chmod 755 -R /root/

#COPY ../../web /


EXPOSE 80 2222

# NOTE SSH Entrypoint normally goes here but image aleady had entrypoint so added SSH code in docker-php-entrypoint file. This seems to work for Azure.
# ENTRYPOINT [ "./entrypoint.sh" ]