#!/bin/sh

until nc -z notifier_app_rabbitmq 5672; do
  echo "Waiting for RabbitMQ..."
  sleep 2
done

sleep 5

chown -R www-data:www-data /var/www/notifier-app/storage
chown -R www-data:www-data /var/www/notifier-app/bootstrap/cache

chmod -R 775 /var/www/notifier-app/storage
chmod -R 775 /var/www/notifier-app/bootstrap/cache


exec "$@"