#
# Makefile
#

.PHONY: help
.DEFAULT_GOAL := help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

clean: ## cleans all dependencies
	rm -rf vendor

prod: ## installs all vendors in prod mode
	COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader
	yarn install --production=true
	yarn encore prod

dev: ## installs all vendors in dev mode
	COMPOSER_MEMORY_LIMIT=-1 composer install -n 
	patch -t vendor/symfony/error-handler/ErrorHandler.php custom/patches/dev/SymfonyErrorHandler.patch
	yarn install --production=false
	yarn encore dev
	bin/console d:s:u -f 

watch: ## webpack watcher 
	yarn encore dev --watch

worker-loop: ## starts the worker in a loop
	while :; do php bin/console messenger:consume async_checks --limit=10 -v; done

worker: ## starts the worker
	php bin/console messenger:consume async_checks -v

scheduler: ## starts the scheduler
	php bin/console orcano:scheduler:run -v

result-worker: ## starts the result worker
	php bin/console messenger:consume async_results -v

test: ## runs all tests
	php vendor/bin/phpunit --configuration=phpunit.xml 

stan: ## starts the PHPStan analyzer
	php vendor/bin/phpstan --memory-limit=-1 analyse 

csfix: ## Starts the PHP CS Fixer [mode=no-dry-run] default is dry-run
ifndef mode
	@php ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.php --dry-run
else ifeq ($(mode), no-dry-run)
	php ./vendor/bin/php-cs-fixer fix --config=./.php-cs-fixer.php
endif
