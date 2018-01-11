PHPUNIT_CONFIG_PREPEND =
MARIADB_HOST = mariadb
MONGODB_HOST = mongo

run-integration-test:
	./phpunit -c phpunit$(PHPUNIT_CONFIG_PREPEND).xml tests/Integration
.PHONY: run-integration-test

prepare-phpunit-config:
	cp phpunit$(PHPUNIT_CONFIG_PREPEND).xml.dist phpunit$(PHPUNIT_CONFIG_PREPEND).xml
.PHONY: prepare-phpunit-config

prepare-test-database:
	mysql -h $(MARIADB_HOST) -uroot < tests/Integration/database/prepare_mariadb.sql \
	&& mysql -h $(MARIADB_HOST) -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/01_event_streams_table.sql \
	&& mysql -h $(MARIADB_HOST) -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/02_projections_table.sql \
	&& mongo $(MONGODB_HOST)/walletaccountant_test < tests/Integration/database/prepare_mongodb.js
.PHONY: prepare-test-database

test-integration: prepare-phpunit-config prepare-test-database run-integration-test
.PHONY: test-integration

test-integration-travis: PHPUNIT_CONFIG_PREPEND = -travis
test-integration-travis: MARIADB_HOST = 127.0.0.1
test-integration-travis: MONGODB_HOST = 127.0.0.1
test-integration-travis: test-integration
.PHONY: test-integration-travis
