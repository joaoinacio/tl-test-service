PHPCS=vendor/bin/phpcs
PHPSTAN=vendor/bin/phpstan
PHPUNIT=vendor/symfony/phpunit-bridge/bin/simple-phpunit

# Output the help for each task
# @see https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help lint check test tests

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

lint: ## phpcs - coding style check (https://github.com/squizlabs/PHP_CodeSniffer)
	$(PHPCS) --standard=PSR2 -n -p src/

check: ## phpstan - static code analysis (https://github.com/phpstan/phpstan)
	$(PHPSTAN) analyse -l 6 src/

test: ## phpunit - unit tests
	$(PHPUNIT)

tests: lint check test ## Run all checks and tests in order