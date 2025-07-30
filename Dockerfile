FROM php:8.3.6-apache

WORKDIR /var/www

# Install tools && libraries
RUN apt-get -y update \
	&& apt-get -y upgrade \
	&& apt-get install -y --fix-missing \
		apt-utils nano wget vim dialog locales \
		build-essential libzip-dev zip tcl gnupg mariadb-client libmcrypt-dev \
		zlib1g-dev libicu-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev gettext jq \
	&& rm -rf /var/lib/apt/lists/*


# Composer
RUN wget --output-document - --quiet https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# PHP Extensions
RUN docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install calendar \
	&& docker-php-ext-install sockets \
	&& docker-php-ext-install -j$(nproc) intl \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg  \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-install gettext


# Configure XDebug
ENV XDEBUG_VERSION=3.4.1
RUN if [ "$(pecl list xdebug-${XDEBUG_VERSION})" = "\`xdebug-${XDEBUG_VERSION}' not installed" ]; then \
	yes | pecl install xdebug-${XDEBUG_VERSION} \
	&& echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "xdebug.trigger_name=true" >> /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "session.use_only_cookies=0" >> /usr/local/etc/php/conf.d/session.ini \
	&& echo "session.use_cookies=0" >> /usr/local/etc/php/conf.d/session.ini; \
	fi;


# Enable apache modules
RUN a2enmod rewrite headers

# Configure v-host for apache
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g;s/<VirtualHost \*:80>/<VirtualHost _default_:*>/' /etc/apache2/sites-available/*.conf

RUN adduser --uid 1000 --disabled-password --gecos '' docker-user \
	&& usermod -a -G www-data docker-user \
	&& usermod -a -G docker-user www-data \
	&& chown docker-user:docker-user /var/www

USER docker-user

EXPOSE 80

CMD ["apache2-foreground"]