
# Life & Me Birthday Ceremony 

A test project 

----

## Installation

- Clone the repository
- Install dependencies and run this commands
```bash
composer install
npm install
npm run build
php artisan key:generate
``` 

    
## Config
- Copy the environment example file and create new .env file
- Configure admin user in the .env file:
```bash
ADMIN_NAME="علیرضا"
ADMIN_FAMILY="جعفری مقدم"
ADMIN_EMAIL=admin@example.com
ADMIN_PASSWORD=lifeandme123
``` 
- Run database migrations
- Seed the database with initial data (creates admin user and test employees):
```bash
php artisan db:seed
``` 

### Ceremony Date Selection
To select a ceremony date, use the following command:
```bash
php artisan birthday:date
``` 
