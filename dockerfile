FROM php:8.2-apache

# Argumentos para UID y GID del usuario del host
ARG UID=1000
ARG GID=1000

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libpq-dev libzip-dev zip curl \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Node.js 20 para Vite/Tailwind
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g pnpm

# Instalar Composer globalmente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Activar mod_rewrite
RUN a2enmod rewrite

# Cambiar DocumentRoot al proyecto moneyLanding/public
RUN sed -i 's#/var/www/html#/var/www/html/moneyLanding/public#g' /etc/apache2/sites-available/000-default.conf

# Configurar Apache para Laravel
RUN printf "<Directory /var/www/html/moneyLanding/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>\n" > /etc/apache2/conf-available/z-laravel.conf \
    && a2enconf z-laravel

# Configurar www-data con el mismo UID/GID que el usuario del host
RUN groupmod -g ${GID} www-data && \
    usermod -u ${UID} -g www-data www-data && \
    chown -R www-data:www-data /var/www/html

# Variables de entorno para Apache
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
