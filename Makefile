APP_NAME=$(shell cat composer.json | jq -r ".name")

# HELP (https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html)
.PHONY: help

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# DOCKER TASKS
build: ## Build the image
	docker build . -t $(APP_NAME):latest;

rm: ## Remove the image
	docker image rm $(APP_NAME):latest -f;

# PHP TASKS
install:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer install;

update:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer update;

unit:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer unit;

lint:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer lint;

fmt:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer fmt;

fmt-fix:
	docker run --volume $(shell pwd):/usr/src/app --user $(shell id -u ${USER}):$(shell id -g ${USER}) $(APP_NAME):latest composer fmt:fix;