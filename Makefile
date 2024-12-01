DOCKER_ENABLED=1

include ./.boing/makes/symfony.mk

create-directories: create-uploads-directories
.phony: create-directories

create-uploads-directories:
	@mkdir -p \
		public/uploads/discover-pack \
		public/uploads/energy \
		public/uploads/hydration \
		public/uploads/iced-tea \
		public/uploads/merch \
		public/uploads/shaker
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
		public/uploads/merch/* \
		public/uploads/shaker/*
.phony: purge-uploads

launch-consumer:
	@$(php) bin/console messenger:consume scheduler_weekScrap
.phony: consume-messages

stop-consumer:
	@$(php) bin/console messenger:stop-workers
.phony: stop-consumer

deploy:
	@git pull
	@$(php) composer install
	@$(php) bin/console doctrine:migrations:migrate --no-interaction
	@$(php) bin/console cache:clear
	@make stop-consumer
	@make launch-consumer
.PHONY: deploy
