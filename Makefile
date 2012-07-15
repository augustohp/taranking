test:
	@cd tests; phpunit .

testdox:
	@cd tests; phpunit --testdox .	

coverage:
	@cd tests; phpunit --coverage-html=reports --coverage-text .