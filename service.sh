php artisan queue:work --tries=3 > ./storage/logs/queue.log &
php artisan websockets:serve > ./storage/logs/websockets.log &