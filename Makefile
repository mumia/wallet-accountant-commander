PHPUNIT_CONFIG_PREPEND =
MARIADB_HOST = mariadb
MONGODB_HOST = mongo

run-unit-test:
	@./phpunit -c phpunit$(PHPUNIT_CONFIG_PREPEND).xml tests/Unit
.PHONY: run-unit-test

run-functional-test:
	@./phpunit -c phpunit$(PHPUNIT_CONFIG_PREPEND).xml tests/Functional
.PHONY: run-unit-test

run-integration-test:
	@./phpunit -c phpunit$(PHPUNIT_CONFIG_PREPEND).xml tests/Integration
.PHONY: run-integration-test

prepare-phpunit-config:
	@cp phpunit$(PHPUNIT_CONFIG_PREPEND).xml.dist phpunit$(PHPUNIT_CONFIG_PREPEND).xml
.PHONY: prepare-phpunit-config

prepare-test-database:
	@mysql -h $(MARIADB_HOST) -uroot < tests/Integration/database/prepare_mariadb.sql \
	&& mysql -h $(MARIADB_HOST) -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/01_event_streams_table.sql \
	&& mysql -h $(MARIADB_HOST) -u root events_test < ./vendor/prooph/pdo-event-store/scripts/mariadb/02_projections_table.sql \
	&& mongo $(MONGODB_HOST)/walletaccountant_test < tests/Integration/database/prepare_mongodb > /dev/null
.PHONY: prepare-test-database

tests: test test-integration
.PHONY: tests

test: prepare-phpunit-config run-unit-test run-functional-test
.PHONY: test

test-integration: prepare-phpunit-config prepare-test-database run-integration-test
.PHONY: test-integration

test-integration-travis: PHPUNIT_CONFIG_PREPEND = -travis
test-integration-travis: MARIADB_HOST = 127.0.0.1
test-integration-travis: MONGODB_HOST = 127.0.0.1
test-integration-travis: test-integration
.PHONY: test-integration-travis

# local docker
build-env:
	docker-compose up --build --remove-orphans
.PHONY: build-env

shell-env:
	docker-compose exec php /bin/sh
.PHONY: shell-env

sql-env:
	docker-compose exec mariadb mysql -u root
.PHONY: sql-env

# database
install-db:
	mysql -h mariadb -u root < ./docker/php-fpm/install_db.sql \
    && mysql -h mariadb -u root events < ./vendor/prooph/pdo-event-store/scripts/mariadb/01_event_streams_table.sql \
    && mysql -h mariadb -u root events < ./vendor/prooph/pdo-event-store/scripts/mariadb/02_projections_table.sql
.PHONY: install-db
