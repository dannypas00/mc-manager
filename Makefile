SHELL := bash

ENV ?= local

# Replace these values with your own, escape spaces with backslashes (in fullname for example)
GITHUB_URL ?= github\.com\/dannypas00\/mc-manager
PROJECT_NAMESPACE ?= dannypas00
PROJECT_NAME ?= mc-manager
DEVELOPER_FULLNAME ?= Danny Pas
DEVELOPER_USERNAME ?= dannypas00
DEVELOPER_EMAIL ?= github@dannypas.nl

# Replacement map using sed (see $(TEMPLATES) target below
TEMPLATE_GITHUB_URL = github\.com\/dannypas00\/laravel-template
TEMPLATES = $(TEMPLATE_GITHUB_URL)/$(GITHUB_URL) laravel-template-namespace/$(PROJECT_NAMESPACE) laravel-template-project/$(PROJECT_NAME) laravel-template-fullname/$(DEVELOPER_FULLNAME) laravel-template-username/$(DEVELOPER_USERNAME) laravel-template@example.com/$(DEVELOPER_EMAIL)

NO_DOCKER ?= false

DOCKER ?= docker
DOCKER_COMPOSE ?= $(DOCKER) compose

PHP_CONTAINER = $(DOCKER_COMPOSE) run frank
NODE_CONTAINER = $(DOCKER_COMPOSE) run node

ifeq ($(NO_DOCKER), true)
PHP_CONTAINER =
endif

PHP ?= $(PHP_CONTAINER) php
COMPOSER ?= $(PHP_CONTAINER) composer
NPM ?= $(NODE_CONTAINER) npm

# First target not starting with "." is default target
all: project-setup

.PHONY: template $(TEMPLATES)
template: $(TEMPLATES)
$(TEMPLATES):
	@# Get all files containing the TEMPLATE_PATTERN and replace it with PROJECT_NAME.
	@# This fails if no files are found (script has already run), hence the || true.
	@grep -rl "laravel-template" . --exclude-dir=public/build --exclude-dir=vendor --exclude-dir=node_modules --exclude-dir=.idea --exclude=Makefile --exclude-dir=.git | xargs sed -i 's/$(@)/g' || true

.PHONY: prod clean install deploy project-setup clear-cache dependencies
install: $(TEMPLATES) dependencies .env.example docker-build composer.lock package-lock.json composer.json package.json docker app-key
project-setup: install init-db test-integration vendor/autoload.php

dependencies:
	@(command -v npm > /dev/null) || (echo "NPM not installed" && exit 127)
	@(command -v docker > /dev/null) || (echo "Docker not installed" && exit 127)
	@(docker compose > /dev/null) || (echo "Docker compose plugin not installed" && exit 127)

clean:
	$(DOCKER_COMPOSE) down -v
	rm -rf composer.lock package-lock.json vendor node_modules bootstrap/cache/*.php public/build

deploy: install clear-cache resources/js/ migrate vendor/autoload.php
	$(PHP) artisan optimize

clear-cache:
	$(PHP) artisan optimize:clear

.env.example:
	@# Copy env.example file if env file doesn't exist yet
	[[ -f .env ]] || cp .env.example .env

composer.json: composer.lock
composer.lock:
ifeq ($(ENV), local)
	$(COMPOSER) install --prefer-dist
else
	$(COMPOSER) install --prefer-dist --no-scripts --no-plugins --no-interaction --no-progress --no-dev --no-suggest --optimize-autoloader
endif
	$(COMPOSER) clear-cache --gc

vendor/autoload.php:
ifeq ($(ENV), local)
	$(COMPOSER) dump-autoload
else
	$(COMPOSER) dump-autoload --classmap-authoritative --apcu
endif

package.json:
ifeq ($(ENV), local)
	$(NPM) ci
else
	$(NPM) ci --omit=dev
endif

package-lock.json:
ifeq ($(ENV), local)
	$(NPM) install
else
	$(NPM) install --omit=dev
endif

.PHONY: init-db drop-db migrate seed
init-db: drop-db docker migrate seed
migrate: database/migrations/
seed: database/seeders/

drop-db:
ifeq ($(ENV), local)
	$(PHP) artisan db:wipe || true
endif

database/migrations/: composer.lock docker
ifeq ($(ENV), local)
	$(PHP) artisan migrate -n
else
	$(PHP) artisan migrate
endif

database/seeders/: drop-db database/migrations/ database/factories/
ifeq ($(ENV), local)
	$(PHP) artisan db:seed --class=Database\\Seeders\\DatabaseSeeder
endif

.PHONY: docker-build docker down up restart
docker-build:
ifneq ($(NO_DOCKER), true)
ifeq ($(ENV), local)
	$(DOCKER_COMPOSE) --profile dev pull
	$(DOCKER_COMPOSE) --profile dev build
else
	$(DOCKER_COMPOSE) pull
	$(DOCKER_COMPOSE) build
endif
endif

up: docker
docker:
ifneq ($(NO_DOCKER), true)
ifeq ($(ENV), local)
	$(DOCKER_COMPOSE) --profile dev up -d --wait --remove-orphans
else
	$(DOCKER_COMPOSE) --profile prod up -d --wait --remove-orphans
endif
endif

down:
ifneq ($(NO_DOCKER), true)
ifeq ($(ENV), local)
	$(DOCKER_COMPOSE) --profile dev down
else
	$(DOCKER_COMPOSE) --profile prod down
endif
endif

restart:
ifneq ($(NO_DOCKER), true)
ifeq ($(ENV), local)
	$(DOCKER_COMPOSE) --profile dev restart
else
	$(DOCKER_COMPOSE) --profile prod restart
endif
endif

.PHONY: test-integration
test-integration: docker
ifeq ($(ENV), local)
	$(PHP) artisan test --env=integration --testsuite=Integration
endif

.PHONY: app-key
app-key: docker
	@# Only generate an app key if the .env doesn't have on yet
	(grep "^APP_KEY=$$" .env && $(PHP) artisan key:generate) || true

resources/js/: package-lock.json
	@# If public/hot is present, laravel will try to serve from vite server
	@rm public/hot || true
	$(NPM) run build
