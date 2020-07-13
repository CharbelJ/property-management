## Description

Property manegement tool, which handles different APIs.

## Getting started

#### Prerequisites:

- PHP 7.3 or higher
- MySQL
- Composer

You can also set this project using Docker, by following the steps in the following link

[How to Dockerize a Laravel Application](https://engineering.carsguide.com.au/how-to-dockerize-a-laravel-application-77a24ba669c5)

#### Setup:

1. Open a new instance of the terminal and clone the repo.
    ```
    $ git clone 
    ```

2. Navigate to the root directory of the project 
    ```
    $ docker-compose up -d
    ```

3. Install all composer packages included in composer.json
    ```
    $ composer install
    ```

4. Create a .env file from the existing .env.example
    ```
    $ cp .env.example .env
    ```

5. Generate a Laravel App Key.
    ```
    $ php artisan key:generate
    ```
   
6. Modify the following field in your .env file to use the value specified
   ```
   DB_DATABASE=propertymanagement
   ```
   
7. Run the database migrations.
    ```
    $ php artisan migrate
    ```
   
8. Seed the database.
   ```
   $ php artisan db:seed
   ```

## Running Tests

To run the tests you should navigate to the root directory of the application.

1. Copy the existing .env file to a new .env.testing file
    ```
    $ cp .env .env.testing
    ```

2. Modify the following field in your .env file to use the value specified
   ```
   DB_DATABASE=propertymanagementtesting
   ```
   
3. Run the database migrations.
   ```
   $ php artisan migrate
   ```

4. Run the tests
    ```
    $ vendor/bin/phpunit
    ```
