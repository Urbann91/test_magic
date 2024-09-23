DOCKER_COMPOSE = docker-compose

build:
	$(DOCKER_COMPOSE) build --no-cache

up:
	$(DOCKER_COMPOSE) up -d --force-recreate

composer-install:
	$(DOCKER_COMPOSE) run --rm app composer install

migrate:
	$(DOCKER_COMPOSE) run --rm app php bin/console doctrine:migrations:migrate -n

fixtures:
	$(DOCKER_COMPOSE) run --rm app php bin/console doctrine:fixtures:load -n

phpcs:
	$(DOCKER_COMPOSE) run --rm app vendor/bin/phpcs

phpcbf:
	$(DOCKER_COMPOSE) run --rm app vendor/bin/phpcbf

reset: build up composer-install migrate fixtures