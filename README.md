# Instalar dependências do Composer
composer install --optimize-autoloader --no-dev

# Instalar dependências do NPM e compilar assets
npm install
npm run prod

# Gerar chave da aplicação
php artisan key:generate

# Migrar banco de dados
php artisan migrate --force

# Cachear e limpar configurações, rotas e views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Ajustar permissões de diretório
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache


## Command Cron Windows

schtasks /create /sc minute /mo 5 /tn "Laravel Scheduler" /tr "C:\xampp\htdocs\argos\laravel_scheduler.bat"