# Inpex ID - Guia de Instalação

Bem-vindo ao Inpex ID! Este guia ajudará você a configurar e rodar o projeto de forma simples e direta.

---

## Requisitos

- **Apache2**
- **MySQL**
- **PHP 8.2**
- **Node ^20.14**
- **NPM ^10.8**
- **Composer ^2.0**

---

## Passo 1: Instalar Apache2, MySQL e Composer

```bash
sudo apt update
sudo apt upgrade

sudo apt install apache2
sudo systemctl status apache2

sudo apt install php libapache2-mod-php php-mysql php-cli

sudo apt install mysql-server
sudo mysql_secure_installation

sudo mysql -u root -p
CREATE DATABASE inpexid;
CREATE USER 'inpexid'@'localhost' IDENTIFIED BY 'inpexid';
GRANT ALL PRIVILEGES ON inpexid.* TO 'inpexid'@'localhost';
FLUSH PRIVILEGES;
EXIT;

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer

sudo apt install nodejs npm

```
---

## Passo 2: Configurar o Virtualhost

```bash
sudo nano /etc/apache2/sites-available/inpexid.conf
```

Copie e cole no nano e depois apertar CRTL+O e CTRL+X

```bash
<VirtualHost *:8000>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/inpex_id/public

    <Directory /var/www/html/inpex_id>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    LimitRequestBody 1048576000
    Timeout 300
</VirtualHost>
```

---

## Passo 3: Reiniciar os Serviços

```bash
sudo a2ensite inpexid.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## Passo 4: Clonar o Repositório

```bash
git clone https://github.com/GuilhermeAndrade-Teltex/inpex_id.git
cd inpex_id
git reset --hard origin/master
```

---

## Passo 5: Instalar Dependências

Composer:
```bash
composer install --optimize-autoloader --no-dev
```

NPM:
```bash
npm run build
```

---

## Passo 6: Configurar Permissões de Diretório

```bash
sudo chown -R $USER:$USER /var/www/html/inpex_id
sudo chown -R www-data:www-data storage 
sudo chown -R www-data:www-data bootstrap/cache 
sudo chmod -R 775 storage 
sudo chmod -R 775 bootstrap/cache
```

---

## Passo 7: Configurar o Laravel

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan config:cache
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan view:cache
```

---

## Passo 8: Monitorar Logs

```bash
sudo tail -f /var/log/apache2/error.log
```

---

## Passo 9: Configurar Tarefas Agendadas

```bash
crontab -e
```

Adicionar a linha ao crontab:

```bash
* * * * * php /var/www/html/inpex_id/artisan schedule:run >> /dev/null 2>&1
```

---

## Passo 10: Verificar as tarefas agendadas

```bash
php artisan schedule:list
```
