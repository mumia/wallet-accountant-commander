#!/bin/sh

while ! nc -z mariadb 3306 < /dev/null
do
    echo "Waiting for MySQL to start"
    sleep 10
done

# Wait for mysql to start
mysql -h mariadb -u root < /root/install_db.sql
mysql -h mariadb -u root events < ./vendor/prooph/pdo-event-store/scripts/mariadb/01_event_streams_table.sql
mysql -h mariadb -u root events < ./vendor/prooph/pdo-event-store/scripts/mariadb/02_projections_table.sql
