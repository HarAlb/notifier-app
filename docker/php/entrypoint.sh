#!/bin/sh

chown -R www-data:www-data /var/www/notifier-app/storage
chown -R www-data:www-data /var/www/notifier-app/bootstrap/cache

chmod -R 775 /var/www/notifier-app/storage
chmod -R 775 /var/www/notifier-app/bootstrap/cache

exec "$@"