# Use a imagem base do PHP com Apache
FROM php:8.2-apache

# Instale as dependências necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    gnupg \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql zip

# Instale o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instale o Node.js e npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Copie os arquivos do projeto
COPY . /var/www/html

# Configure o Apache para o Laravel
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Configure o Apache para servir a pasta 'public' e adicionar um VirtualHost customizado
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    ServerAdmin webmaster@inpexid.com.br' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ServerName inpexid.com.br' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ErrorLog ${APACHE_LOG_DIR}/inpexid.com.br-error.log' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    CustomLog ${APACHE_LOG_DIR}/inpexid.com.br-access.log common' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Instale as dependências do Composer
RUN composer install --optimize-autoloader --no-dev

# Instale as dependências do Node.js
RUN npm install

# Construa os assets do Vite
RUN npm run build

# Configure permissões
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponha a porta 80
EXPOSE 80

# Iniciar o Apache
CMD ["apache2-foreground"]
