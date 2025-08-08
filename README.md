# Microblog App
This is a CRUD microblog app that allows admin user to create, update and delete blog posts, 
as of users, everyone can read the blog posts, system allows registration and login, but is not needed
for the purpose of reading the articles.

## Features
- User authorization and authentication
- CRUD for managing blog posts
- Guest users actions

## Requirements
- PHP >= 7.4
- MySQL >= 5.7
- Composer

## Instalation
1. Clone the repository
    ```bash
    git clone git@github.com:HristiyanBorisov/Microblog.git
    cd microblog
   ```
2. Install dependencies
    ```bash
     composer install
   ```
3. Copy .env.example to .env and configure mysql variables
    ``` bash
     cp .env.example .env
   ```
4. Set up database
- Open database/schema.sql and execute locally
- In order for the admin panel to be visible, after creating user, set in database table users->admin=1
5. Start the local server
    ``` bash
    php -S localhost:8000 -t public
   ```
6. Execute tests
   ``` bash
   vendor/bin/phpunit
   ```
