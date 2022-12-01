## Steps to install

#### 1. Clone repository and install dependencies 
```bash
git clone https://github.com/balkanservices/ficosota-labtests.git
cd ficosota-labtests/
touch database/database.sqlite
composer install
cp .env.example .env
php artisan key:generate
```

#### 2. Edit .env file and change the following parameters:
```
DB_DATABASE=/FULL_PATH_TO/ficosota-labtests/database/database.sqlite
```

#### 3. Create DB tables and seed them
```bash
php artisan migrate
php artisan db:seed
```