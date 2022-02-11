# Cookies Project

## What we have done

- Create a new lravel project
- Implement auth in it
-- For user register & login as user must be login to buy cookies
- Add wallet column added in user table with default amount 10
-- Wallet column added to users table & set value 10 by default as mentioned
- create home page to buy cookies by entering amount
-- An interface created for users to buy cookies
- validate all condition
-- Handle validation like if your are not logged in, if user enter invalid amount or more amount than wallet amount 
- give functionality to buy cookies
-- If user pass every condition then user can buy cookies

## How to run the project after cloning from git
- Run `composer install` in terminal
- Run `php artisan key:generater` in terminal
- Create a database and Then setup your `DB_DATABASE`, `DB_USERNAME` & DB_PASSWORD (if any) in .env file.
- Run `php artisan migrate` in terminal
- Run `php artisan serve` in terminal
- then browse the project in http://127.0.0.1:8000/

## How to run the project from zip file
- Unzip the project
- Create a database and give it name laravel Then setup your `DB_USERNAME` (if not root) & `DB_PASSWORD` (if any) in .env file.
- Run `php artisan migrate` in terminal
- Run `php artisan serve` in terminal
- then browse the project in http://127.0.0.1:8000/