DOCKER_ENABLED=1

include ./.env
include ./.env.local
include ./.boing/makes/symfony.mk

create-directories: create-uploads-directories
.phony: create-directories

create-uploads-directories:
	@mkdir -p \
		public/uploads/discover-pack \
		public/uploads/energy \
		public/uploads/hydration \
		public/uploads/iced-tea \
		public/uploads/milkshake \
		public/uploads/merch \
		public/uploads/shaker \
		public/uploads/default
.phony: create-uploads-directories

purge: purge-mock-templates purge-uploads
.phony: purge

purge-mock-templates:
	@rm -rf mock/*
.phony: purge-mock-templates

purge-uploads:
	@rm -rf \
		public/uploads/discover-pack/* \
		public/uploads/energy/* \
		public/uploads/hydration/* \
		public/uploads/iced-tea/* \
		public/uploads/milkshake/* \
		public/uploads/merch/* \
		public/uploads/shaker/* \
		public/uploads/default/*
.phony: purge-uploads

launch-consumer:
	@$(php) bin/console messenger:consume scheduler_weekScrap
.phony: consume-messages

stop-consumer:
	@$(php) bin/console messenger:stop-workers
.phony: stop-consumer

deploy:
	@git pull
	@$(composer) install
	@$(MAKE) database-dump
	@$(php) bin/console doctrine:migrations:migrate --no-interaction
	@$(php) bin/console cache:clear
.PHONY: deploy
