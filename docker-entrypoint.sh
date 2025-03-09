#!/bin/sh

# Sai imediatamente se ocorrer um erro
set -e

# Configura o ambiente de testes
cp .env.testing.example .env.testing

# Instala as dependências do Composer
composer install --no-interaction --optimize-autoloader

# Gera a chave da aplicação se não existir
php artisan key:generate --no-interaction

# Copia a chave para o ambiente de testes
APP_KEY=$(grep "^APP_KEY=" .env | cut -d= -f2-)
sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env.testing

# Limpa caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Executa as migrations e os seeders
php artisan migrate --seed --force

# Instala o Passport
php artisan passport:install --force

