FROM php:8.1-fpm

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
    zip \
    unzip \
    openssl \
    libzip-dev  \
    libonig-dev  \
    libicu-dev \
    libpq-dev \
    autoconf  \
    pkg-config  \
    libssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath intl
RUN pecl install -o -f redis && rm -rf /tmp/pear && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN pecl install mongodb \
&&  echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && chown -R $user:$user /home/$user
#RUN ls -s /home/sammy/samara /var/www

# Install sudo
RUN apt-get update && apt-get -y install sudo
RUN echo "$user:$user" | chpasswd && adduser $user sudo

# Set working directory
WORKDIR /var/www/html
USER $user