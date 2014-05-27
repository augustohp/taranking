COMPOSER=bin/composer.phar
PHPUNIT=bin/phpunit
BEHAT=bin/behat
COMPOSER_ARGS=--no-ansi --profile --no-interaction
PHPUNIT_ARGS=--verbose --configuration tests/phpunit.xml

clean: permission
	@echo "Cleans everything (database included!)"
	bin/doctrine orm:schema-tool:drop --dump-sql
	bin/doctrine orm:schema-tool:drop --force
	-rm ${COMPOSER}

permission:
	chmod a+x ${COMPOSER}
	chmod a+x bin/doctrine
	-chmod a+w .
	-chmod a+rxw database.sqlite

install-composer:
	@echo "Installing (or updating) composer"
	test -f ${COMPOSER} && ${COMPOSER} self-update || curl -sS https://getcomposer.org/installer | php -- --install-dir=bin

composer:
	${COMPOSER} install ${COMPOSER_ARGS}

doctrine: permission
	bin/doctrine orm:schema-tool:update --dump-sql
	bin/doctrine orm:schema-tool:update --force

dev: install-composer
	${COMPOSER} install --dev ${COMPOSER_ARGS}

install: permission install-composer composer doctrine
	@echo "Fixed permissions, got dependencies and created/updated database"

test: phpunit behat

behat:
	${BEHAT} -vvv

phpunit:
	${PHPUNIT} ${PHPUNIT_ARGS} tests

phpunit-testdox:
	${PHPUNIT} ${PHPUNIT_ARGS} --testdox tests

phpunit-coverage:
	${PHPUNIT} ${PHPUNIT_ARGS}--coverage-html=reports --coverage-text tests

wip:
	${PHPUNIT} ${PHPUNIT_ARGS} --group wip tests
	${BEHAT} --tags wip -vvv
