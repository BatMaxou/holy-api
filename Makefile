DOCKER_ENABLED=1

include ./.boing/makes/symfony.mk

create-directories: create-uploads-directories
.phony: create-directories

create-uploads-directories:
	@mkdir -p \
		uploads/discover-pack \
		uploads/energy \
		uploads/hydration \
		uploads/iced-tea \
		uploads/merch \
		uploads/shaker
.phony: create-uploads-directories

purge: purge-mock-templates purge-uploads
.phony: purge

purge-mock-templates:
	@rm -rf mock/*
.phony: purge-mock-templates

purge-uploads:
	@rm -rf \
		uploads/discover-pack/* \
		uploads/energy/* \
		uploads/hydration/* \
		uploads/iced-tea/* \
		uploads/merch/* \
		uploads/shaker/*
.phony: purge-uploads

launch-consumer:
	@$(php) bin/console messenger:consume scheduler_weekScrap
.phony: consume-messages

stop-consumer:
	@$(php) bin/console messenger:stop-workers
.phony: stop-consumer
