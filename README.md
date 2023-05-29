![Devcore](https://devcore.app/img/logo.png "Devcore")

# DEVCORE

This is the laravel server of devcore app (backend with graphql api (https://lighthouse-php.com/)).

To run this project you must clone this project and configure on your machine.

For convenience, the project contains a full-featured docker-compose project.

After the project it's cloned, please copy the .env.example file and put your configuration params (when applicable)

## SYSTEM REQUIREMENTS

Your production server _**must satisfy**_ the following requirements:
- nginx 
- php7.3 (with all of the laravel requirements https://laravel.com/docs/7.x/installation)
- mysql 
- beanstalkd (enabled as a service to process the message queue)
- redis (cache server)
- supervisor (to run the websockets and workers as a service)


## NGINX CONFIGURATION

If you want to install this on your production server, please take the file ``./docker/nginx/conf.d/app.conf`` as example of how to configure your web server.

## INSTALLATION

-   If you are using docker:
    `./dartisan app:install`

-   If you are _NOT_ using docker:
    `./artisan app:install`

-   `php artisan app:install --seed
    Installs the application and seeds master user with the following credentials:
    user: root@devcore.test
    pw: rockrockrock

*   you can add `--seed` option to seed the database with dummyt data (*very useful on dev*)

## QUEUES AND WEBSOCKETS

The app uses a deferer queue workers and websockets:

To run the queue: `artisan queue:work`

To run the websockets `artisan websockets:serve`

> You should install supervisor on your production server to make them work

## CRON

The app needs a cronjob to make the projects schedule work, you should put this on your crontab file:

``` * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1 ```

If you want receive an email every time that the cron finsih a job, put your email addres in the .env file 

## UTILITIES

After every change on the code, you should run the following command:

`artisan app:cache:clean`

`artisan app:cache:clean`

> Please keep in mind, that if you are running supervisor, then you need to restart the processes to keep them sycronized!


## Run Artisian Server

php artisan serve
php artisan serve --host 192.168.10.12 --port 8000



## Update Composer Packages

php -d memory_limit=-1 composer.phar update
php -d memory_limit=-1 composer.phar require doctrine/dbal

## Create Notifications
php artisan make:notification ProjectNextStage

## Run Queue Work 
php artisan queue:work
