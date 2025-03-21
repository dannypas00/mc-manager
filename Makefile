SHELL := bash

ENV ?= local

NO_DOCKER ?= false

DOCKER ?= docker
DOCKER_COMPOSE ?= docker compose
NODE_LOCAL ?= false

PHP_CONTAINER = $(DOCKER_COMPOSE) run --rm php
NODE_CONTAINER = $(DOCKER_COMPOSE) run --rm node

ifeq ($(NO_DOCKER), true)
PHP_CONTAINER =
NODE_LOCAL = true
endif

ifeq ($(ENV), local)
NPM_INSTALL_ARGS =
COMPOSE_PROFILE = --profile dev
else
NPM_INSTALL_ARGS = --omit=dev
COMPOSE_PROFILE = --profile prod
endif

PHP ?= $(PHP_CONTAINER) php
COMPOSER ?= $(PHP_CONTAINER) composer
NPM ?= $(NODE_CONTAINER) npm

MINECRAFT_DOWNLOAD_LINK = https://piston-data.mojang.com/v1/objects/4707d00eb834b446575d89a61a11b5d548d8c001/server.jar

# First target not starting with "." is default target
all: project-setup

.PHONY: prod clean install deploy project-setup clear-cache dependencies
install: $(TEMPLATES) dependencies .env docker-build composer.lock vendor/ node_modules up app-key storage/app/profile-images/
project-setup: install init-db test-integration vendor/autoload.php

dependencies:
ifeq ($(NODE_LOCAL), true)
	@(command -v npm > /dev/null) || (echo "NPM not installed" && exit 127)
endif
	@(command -v docker > /dev/null) || (echo "Docker not installed" && exit 127)
	@(docker compose > /dev/null) || (echo "Docker compose plugin not installed" && exit 127)

clean:
	$(DOCKER_COMPOSE) down -v
	rm -rf composer.lock package-lock.json vendor node_modules bootstrap/cache/*.php public/build

deploy: install clear-cache resources/js/ migrate vendor/autoload.php up
	$(PHP) artisan optimize

clear-cache: up
	$(PHP) artisan optimize:clear

.env:
	@# Copy env.example file if env file doesn't exist yet
	[[ -f .env ]] || cp .env.example .env

vendor/: composer.lock
composer.lock: composer.json
ifeq ($(ENV), local)
	$(COMPOSER) install --prefer-dist
else
	$(COMPOSER) install --prefer-dist --no-scripts --no-plugins --no-interaction --no-progress --no-dev --no-suggest --optimize-autoloader
endif
	$(COMPOSER) clear-cache --gc

vendor/autoload.php: vendor/
ifeq ($(ENV), local)
	$(COMPOSER) dump-autoload
else
	$(COMPOSER) dump-autoload --classmap-authoritative --apcu
endif

node_modules: package-lock.json
ifeq ($(NODE_LOCAL), true)
	npm ci $(NPM_INSTALL_ARGS)
else
	$(NPM) ci $(NPM_INSTALL_ARGS)
endif

package-lock.json: package.json
ifeq ($(NODE_LOCAL), true)
	npm install $(NPM_INSTALL_ARGS)
else
	$(NPM) install $(NPM_INSTALL_ARGS)
endif

.PHONY: init-db drop-db migrate seed
init-db: up drop-db migrate seed
migrate: database/migrations/
seed: database/seeders/

drop-db: up
ifeq ($(ENV), local)
	$(PHP) artisan db:wipe || true
endif

database/migrations/: composer.lock up
ifeq ($(ENV), local)
	$(PHP) artisan migrate -n
else
	$(PHP) artisan migrate
endif

database/seeders/: up
ifeq ($(ENV), local)
	$(PHP) artisan db:seed
endif

.PHONY: docker-build up down
docker-build:
ifneq ($(NO_DOCKER), true)
	$(DOCKER_COMPOSE) $(COMPOSE_PROFILE) build --pull
endif

up: .docker/local/minecraft/server.jar
ifneq ($(NO_DOCKER), true)
	$(DOCKER_COMPOSE) $(COMPOSE_PROFILE) up -d --remove-orphans --wait
endif

down:
ifneq ($(NO_DOCKER), true)
	$(DOCKER_COMPOSE) $(COMPOSE_PROFILE) down
endif

.PHONY: test-integration
test-integration: up
ifeq ($(ENV), local)
	$(MAKE) up
	sleep 5
	$(PHP) artisan test --env=integration --testsuite=Integration
endif

.PHONY: app-key
app-key: .env up
	@# Only generate an app key if the .env doesn't have one yet
	(grep "^APP_KEY=$$" .env && $(PHP) artisan key:generate && $(DOCKER_COMPOSE) $(COMPOSE_PROFILE) restart frank) || true

resources/js/: node_modules
	@# If public/hot is present, laravel will try to serve from vite server
	@rm public/hot || true
	$(NPM) run build

.docker/local/minecraft/server.jar: .docker/local/minecraft/eula.txt .docker/local/minecraft/server.properties
	curl -fsSL $(MINECRAFT_DOWNLOAD_LINK) -o .docker/local/minecraft/server.jar

.docker/local/minecraft/eula.txt:
	echo "eula=true" > .docker/local/minecraft/eula.txt

.docker/local/minecraft/server.properties:
	cp .docker/local/minecraft/server.properties.example .docker/local/minecraft/server.properties

storage/app/profile-images/: up
	$(PHP) artisan storage:link
