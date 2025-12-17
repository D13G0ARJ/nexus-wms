FROM php:8.2-fpm

# Argumentos
ARG user=nexus
ARG uid=1000

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \

    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario del sistema para ejecutar comandos Composer y Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos de permisos y cambiar propietario
COPY --chown=$user:$user . /var/www

# Cambiar al usuario creado
USER $user

# Exponer puerto 9000 y iniciar php-fpm
EXPOSE 9000
CMD ["php-fpm"]
