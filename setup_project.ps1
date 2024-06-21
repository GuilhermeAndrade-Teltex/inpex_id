# Solicita informações do usuário
$AppURL = Read-Host "Enter the application URL"
$CorsightApiUri = Read-Host "Enter the Corsight API URI"
$CorsightApiUsername = Read-Host "Enter the Corsight API username"
$CorsightApiPassword = Read-Host "Enter the Corsight API password"

# Cria o conteúdo do arquivo .env
$envContent = @"
APP_NAME=InpexID
APP_ENV=production
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=$AppURL

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=database

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=inpex_id
DB_USERNAME=root
DB_PASSWORD=T3lt3x@Admin

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

MAIL_MAILER=inpex_id
MAIL_HOST='smtp.gmail.com'
MAIL_PORT=465
MAIL_USERNAME='inpexid@inpex.com.br'
MAIL_PASSWORD='T3lt3x@Admin'
MAIL_ENCRYPTION='ssl'
MAIL_FROM_ADDRESS='inpexid@inpex.com.br'
MAIL_FROM_NAME='InpexID'

CORSIGHT_API_URI=$CorsightApiUri
CORSIGHT_API_USERNAME=$CorsightApiUsername
CORSIGHT_API_PASSWORD=$CorsightApiPassword
QUEUE_CONNECTION=database

VITE_APP_NAME=$AppName
"@

# Escreve o conteúdo no arquivo .env
$envContent | Out-File -FilePath .\.env -Encoding utf8

# Navega para o diretório do projeto
Set-Location -Path "C:\inpex_id"

# Executa os comandos do Docker e Laravel
Write-Host "Starting Docker containers..."
docker-compose up -d --build

Write-Host "Installing Composer dependencies..."
docker-compose exec app composer install --optimize-autoloader --no-dev

Write-Host "Installing Node.js dependencies..."
docker-compose exec app npm install

Write-Host "Building assets..."
docker-compose exec app /bin/sh -c 'npm run build > build.log 2>&1 || true && if [ $? -ne 0 ]; then echo "Build failed. See build.log for details." && cat build.log && exit 1; else echo "Build succeeded." && exit 0; fi'

Write-Host "Running migrations..."
docker-compose exec app php artisan migrate --force

Write-Host "Running database seeders..."
docker-compose exec app php artisan db:seed --force

Write-Host "Generating Laravel app key..."
docker-compose exec app php artisan key:generate --force

Write-Host "Clearing and recaching configuration..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache

Write-Host "Optimizing Laravel..."
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
docker-compose exec app php artisan optimize

Write-Host "Setting up scheduled tasks..."
docker-compose exec app php artisan schedule:run

Write-Host "Project setup completed successfully!"
