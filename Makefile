help:
	@echo "Please use \`make <target>' where <target> is one of"
	@echo "  install                        to setup the dev environment."
	@echo "  test                           to perform tests."
	@echo "  coverage                       to perform tests with code coverage."
	@echo "  phpstan                        to run phpstan"
	@echo "  infection                      to run infection"

install:
	composer install

test:
	php vendor/bin/phpunit

coverage:
	php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text

phpstan:
	php vendor/bin/phpstan analyse

INFECTION_THREADS = $(shell sysctl -n hw.ncpu)

infection:
	php vendor/bin/infection --threads=$(INFECTION_THREADS)
