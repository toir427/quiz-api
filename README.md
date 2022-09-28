# Step 1: Install

Install laravel components with composer

```bash
composer install
```

# Configuration database

## Prepare postgres database

```bash
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" |sudo tee /etc/apt/sources.list.d/pgdg.list
sudo apt update
sudo apt -y install postgresql-13 postgresql-client-13
sudo su - postgres
psql -c "alter user postgres with password 'StrongAdminP@ssw0rd'"
psql

postgres=# \conninfo
postgres=# CREATE DATABASE lljwt;
postgres=# CREATE USER lljwt WITH ENCRYPTED PASSWORD 'lljwt';
postgres=# GRANT ALL PRIVILEGES ON DATABASE lljwt to lljwt;
postgres=# \l
postgres-# \c lljwt
postgres-# \q

```

# Edit laravel configuration file

Copy `.env` file from `.env.example` file if it is not exist. Set database configurations when you create.

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lljwt         # database table name
DB_USERNAME=lljwt         # database user name
DB_PASSWORD=lljwt         # database user password
```

## Create user table

```bash
./artisan migrate
```

# Create VirtualHost

## Apache2 configurations

```apacheconf
<VirtualHost *:80>
        ServerName jwt.lc
        ServerAlias www.jwt.lc

        ServerAdmin webmaster@jwt.lc
        DocumentRoot "/Users/user/Sites/jwt/public"

        ErrorLog "/private/var/log/apache2/jwt-error_log"
        CustomLog "/private/var/log/apache2/jwt-access_log" common

        <Directory /Users/user/Sites/jwt/public >
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
		Require all granted
        </Directory>
</VirtualHost>
```

## hosts file

```
127.0.0.1	jwt.lc www.jwt.lc
```

# API example

## Run application by command line

```bash
./artisan serve # default: http://127.0.0.1:8000
```

## Create user

```bash
curl --location --request POST 'http://127.0.0.1:8000/api/auth/register' \
--header 'Content-Type: application/json' \
--form 'name="Useful"' \
--form 'email="info@jwt.lc"' \
--form 'password="random1"' \
--form 'password_confirmation="random1"'
```

## Check user exist or not

```bash
curl --location --request POST 'http://127.0.0.1:8000/api/auth/login?email=info@jwt.lc&password=random1'
```

# Fake data by seeder and factory

```bash
./artisan migrate:refresh --seed
# or
./artisan migrate
./artisan db:seed
```

## Sail docker

```bash
./vendor/bin/sail up -d
```

## Sail migration and seeds

```bash
./vendor/bin/sail artisan migrate --seed
```

## Key generate

```bash
./vendor/bin/sail artisan key:generate
```
## JWT key generate
```bash
./vendor/bin/sail artisan jwt:secret
```
