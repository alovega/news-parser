FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    cron \
    zip \
    unzip \
    vim \
    systemctl \
    sudo 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd 
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
 
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

RUN chown -R www-data:www-data /var/www

# RUN chmod -R 775 storage

# ENTRYPOINT ["/lib/systemd/systemd"]
# Set working directory
WORKDIR /var/www
# Copy the Systemd unit file
COPY ./config/systemd/user/messenger-worker@.service /etc/systemd/system/messenger-worker@.service
# RUN mkdir -p /var/log/supervisor
# RUN chmod -R 775  /var/log/supervisor
# COPY ./config/messenger-worker.conf /etc/supervisor/conf.d/supervisord.conf
RUN systemctl daemon-reload
RUN systemctl enable messenger-worker@.service
# RUN systemctl start messenger-worker@{1..20}.service
# Add new cron entry
RUN crontab -l | { cat; echo "* * * * * cd /var/www && /usr/local/bin/php bin/console app:add-news >> var/log/cron.log 2>&1"; } | crontab -


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD systemctl start messenger-worker@{1..20}.service; php-fpm

# RUN usermod -aG sudo $user
# New added for disable sudo password
# RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers
# Set as default user
# USER $user
# RUN sudo supervisord

# Start cron service
RUN /etc/init.d/cron start
# RUN /usr/sbin/init