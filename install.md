

composer install
cp .env.example .env
php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockbeci
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate:fresh
php artisan db:seed --class=InitialDatabaseSeeder
php artisan serve

