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
    supervisor \
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

# Set working directory
WORKDIR /var/www
COPY ./config/messenger-worker.conf /etc/supervisor/conf.d/
# Add new cron entry
RUN crontab -l | { cat; echo "* * * * * cd /var/www && /usr/local/bin/php bin/console app:add-news >> var/log/cron.log 2>&1"; } | crontab -


# Start cron service
RUN /etc/init.d/cron start


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

RUN useradd -ms /bin/bash ubuntu && \
    usermod -aG sudo ubuntu
# New added for disable sudo password
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

# Set as default user
USER ubuntu
RUN sudo supervisord