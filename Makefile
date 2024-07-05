ENV ?= local
PROJECT_NAME ?= test12
TEMPLATE_PATTERN = laravel-template

NO_DOCKER ?= false

DOCKER ?= docker
DOCKER_COMPOSE ?= $(DOCKER) compose

PHP_CONTAINER = $(DOCKER_COMPOSE) run php

ifeq ($(NO_DOCKER), true)
PHP_CONTAINER =
endif

PHP = $(PHP_CONTAINER) php
COMPOSER = $(PHP_CONTAINER) composer
NPM = $(PHP_CONTAINER) npm

.DEFAULT_TARGET: init

.PHONY: init
init: $(TEMPLATE_PATTERN) .env.example composer.json package.json docker-compose.yaml app-key init-db test-integration

.PHONY: install
install: composer.lock package-lock.json docker-compose.yaml

.PHONY: $(TEMPLATE_PATTERN)
$(TEMPLATE_PATTERN):
	@# Get all files containing the TEMPLATE_PATTERN and replace it with PROJECT_NAME.
	@# This fails if no files are found (script has already run), hence the || true.
	@grep -rl $(TEMPLATE_PATTERN) . --exclude-dir=vendor --exclude-dir=node_modules --exclude-dir=.idea --exclude=Makefile --exclude-dir=.git | xargs sed -i 's/$(TEMPLATE_PATTERN)/$(PROJECT_NAME)/g' || true

.env.example:
	@# Copy env.example file if env file doesn't exist yet
	[[ -f .env ]] || cp .env.example .env

composer.json: composer.lock
composer.lock:
ifeq ($(ENV), local)
	$(COMPOSER) install --prefer-dist --optimize-autoloader
else
	$(COMPOSER) install --prefer-dist --classmap-authoritative --optimize-autoloader --no-scripts --no-plugins --no-interaction --apcu-autoloader --no-progress --no-dev --no-suggest
endif

package.json:
ifeq ($(ENV), local)
	$(NPM) install
else
	$(NPM) install --omit=dev
endif

package-lock.json:
ifeq ($(ENV), local)
	$(NPM) install
else
	$(NPM) ci --omit=dev
endif

.PHONY: init-db drop-db migrate seed
init-db: docker-compose.yaml drop-db migrate seed
migrate: database/migrations/
seed: database/seeders/

drop-db:
ifeq ($(ENV), local)
	$(PHP) artisan db:wipe || true
endif

database/migrations/: composer.lock docker-compose.yaml
	$(PHP) artisan migrate -n

database/seeders/: database/migrations/ database/factories/
ifeq ($(ENV), local)
	$(PHP) artisan db:seed --class=Database\\Seeders\\DatabaseSeeder
else
	$(PHP) artisan db:seed --class=Database\\Seeders\\LiveSeeder
endif

docker-compose.yaml:
ifneq ($(NO_DOCKER), true)
	$(DOCKER_COMPOSE) up -d --wait
endif

.PHONY: test-integration
test-integration:
	$(COMPOSER) run test-integration

.PHONY: app-key
app-key:
	@# Only generate an app key if the .env doesn't have on yet
	(grep "^APP_KEY=$$" .env && $(PHP) artisan key:generate) || true
