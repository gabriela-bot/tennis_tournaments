FROM php:8.4-fpm

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
    libzip-dev\
    libwebp-dev \
    libfreetype6-dev\
    libjpeg62-turbo-dev \
    libcurl4-openssl-dev\
    zip \
    unzip \
    vim \
    dos2unix

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        xml \
        curl\
	    intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

COPY docker/local/php.ini /etc/php/8.4/fpm/php.ini

COPY docker/local/laravel_start /usr/local/bin/laravel_start

RUN dos2unix /usr/local/bin/laravel_start
RUN chmod +x /usr/local/bin/laravel_start

USER $user

# Set working directory
WORKDIR /var/www

COPY . /var/www

EXPOSE 80
CMD ["bash" ,"-c", "/usr/local/bin/laravel_start"]
