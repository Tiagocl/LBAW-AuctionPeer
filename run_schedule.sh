#!/bin/sh
* * * * * cd /var/www && php artisan app:update-auction-statuses  >> /dev/null 2>&1