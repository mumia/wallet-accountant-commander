test-integration:
	mysql -h mariadb -uroot < tests/Integration/database/prepare_mariadb.sql \
	&& mysql -h mariadb -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/01_event_streams_table.sql \
	&& mysql -h mariadb -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/02_projections_table.sql\
	&& mongo mongo/walletaccountant_test < tests/Integration/database/prepare_mongodb.js \
	&& ./phpunit tests/Integration
.PHONY: test
