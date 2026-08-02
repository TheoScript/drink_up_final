# Usar a imagem oficial do PHP
FROM php:8.2-cli

# Instalar dependências do sistema e extensões necessárias para o PostgreSQL
RUN apt-get update -y && apt-get install -y libpq-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir a pasta de trabalho no servidor
WORKDIR /app

# Copiar todos os arquivos do seu projeto para o servidor
COPY . .

# Instalar as dependências do Laravel
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Rodar as migrations e iniciar o servidor na porta do Render
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
