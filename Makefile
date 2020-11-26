help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  install                        to setup the dev environment."
	@echo "  test                           to perform tests."
	@echo "  coverage                       to perform tests with code coverage."
	@echo "  phpstan                        to run phpstan"

install:
	composer install

test:
	php vendor/bin/phpunit

coverage:
	php vendor/bin/phpunit --coverage-text

phpstan:
	php vendor/bin/phpstan analyse
