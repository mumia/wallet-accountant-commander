#!/bin/sh
set -xe

/root/boot_db.sh

# Detect the host IP to add to whitelist ips (index.php)
export DOCKER_BRIDGE_IP=$(ip ro | grep default | cut -d' ' -f 3)

# See https://shippingdocker.com/xdebug/auto-config/
#echo "xdebug.remote_host="$XDEBUG_HOST >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

composer install --prefer-dist --no-progress --no-suggest

exec php-fpm
