FROM php:8.3-fpm

# Argumentos definidos no docker-compose.yml
ARG user
ARG uid

# Atualizando repositórios e instalando dependências do sistema
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    build-essential \
    libcurl4-openssl-dev \
    libssl-dev \
    sqlite3 \
    libsqlite3-dev

# Limpando o cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalando as extensões do PHP
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Pegando a última versão do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criando o usuário dentro do contêiner
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Definindo o diretório de trabalho
WORKDIR /var/www

# Ajustando permissões dos diretórios
RUN mkdir -p /var/run/php-fpm && \
    chown -R $user:www-data /var/run/php-fpm && \
    chown -R $user:www-data /var/www

# Copiando a configuração do php-fpm
COPY docker-compose/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Definindo o usuário para a execução do contêiner
USER $user
