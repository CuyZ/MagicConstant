.DEFAULT_GOAL := help

.PHONY: help
help: ## Show help message
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m\033[0m\n"} /^[$$()% 0-9a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: install
install: ## to setup the dev environment.
	composer install

.PHONY: test
test: ## to perform unit tests.
	php vendor/bin/phpunit

.PHONY: coverage
coverage: ## to perform unit tests with code coverage.
	php -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text

.PHONY: phpstan
phpstan: ## to run PHPStan
	php vendor/bin/phpstan analyse
