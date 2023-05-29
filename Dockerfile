FROM php:7.3-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    postgresql \
    libpq-dev \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    libzip-dev \
    libzmq3-dev \
    git \
    curl \
    nodejs \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions

RUN docker-php-ext-install pdo_mysql pdo_pgsql pgsql mysqli  mbstring zip exif pcntl opcache
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-enable pgsql pdo_pgsql

#swoole
RUN pecl install swoole \
    && docker-php-ext-enable swoole

RUN pecl install inotify \
    && docker-php-ext-enable inotify

##zeromq
# RUN pecl install zmq-beta \
#     && docker-php-ext-enable zmq


#redis ext
RUN pecl install redis \
    && docker-php-ext-enable redis

#RUN pecl install supervisor \
#    && docker-php-ext-enable supervisor

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

#copy opcache
#COPY .docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini



#Supervisor
RUN mkdir -p /var/log/supervisor
RUN touch /var/log/supervisor/supervisord.log
RUN touch /var/run/supervisor.sock
RUN chmod 644 /var/run/supervisor.sock
RUN chmod 644 /var/log/supervisor/supervisord.log
#COPY ./.docker/supervisor/. /etc/supervisor/conf.d
# COPY ./.docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
# COPY ./.docker/supervisor/workers/. /etc/supervisor/conf.d
# COPY ./.docker/supervisord.conf /etc/supervisor/supervisord.conf
# COPY ./.docker/workers/. /etc/supervisor/conf.d

# Xdebug setup
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


# Prevent error in nginx error.log
RUN mkdir -p /var/log/xdebug
RUN touch /var/log/xdebug/xdebug_remote.log
RUN chmod 777 /var/log/xdebug/xdebug_remote.log


 RUN echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 #RUN echo "xdebug.remote_autostart=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_mode=req" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_log=/var/log/xdebug_remote.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.idekey=php-debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 RUN echo "xdebug.remote_connect_back=Off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
 # docker nginx ip adress
 RUN echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini


# Php-fpm setup
RUN mkdir -p /var/run/php
RUN mkdir /var/log/php-fpm/
RUN touch /var/log/php-fpm/stdout.log
RUN chmod 777 /var/log/php-fpm/stdout.log
RUN touch /var/log/php-fpm/stderr.log
RUN chmod 777 /var/log/php-fpm/stderr.log

# Copy existing application directory contents
# COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

#swoole expose port
EXPOSE 1215

# Expose port 9000 and start php-fpm server
EXPOSE 9000
#CMD ["php-fpm"]
#CMD ["/usr/bin/supervisord"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

