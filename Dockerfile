FROM php:8.4-cli

LABEL maintainer="Laravel Game Project"

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    libzip-dev \
    libssl-dev \
    libbrotli-dev \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip sockets \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar arquivos da aplicação
COPY ./src /var/www

# Definir permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Expor portas
EXPOSE 8000 9501

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
