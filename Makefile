clean: permission
	@echo "Cleans everything (database included!)"
	bin/doctrine orm:schema-tool:drop --dump-sql
	bin/doctrine orm:schema-tool:drop --force

permission:
	chmod a+x bin/composer.phar
	chmod a+x bin/doctrine
	-chmod a+w .
	-chmod a+rxw database.sqlite

composer:
	bin/composer.phar install

doctrine: permission
	bin/doctrine orm:schema-tool:update --dump-sql
	bin/doctrine orm:schema-tool:update --force

install: permission doctrine
	@echo "Fixing permissions, getting dependencies and creating/updating database"

test:
	@cd tests; phpunit .

testdox:
	@cd tests; phpunit --testdox .	

coverage:
	@cd tests; phpunit --coverage-html=reports --coverage-text .