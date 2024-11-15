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

purge-uploads:
	@rm -rf \
		uploads/discover-pack/* \
		uploads/energy/* \
		uploads/hydration/* \
		uploads/iced-tea/* \
		uploads/merch/* \
		uploads/shaker/*
.phony: purge-uploads
