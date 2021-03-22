#!/bin/bash

#docker-compose build
docker-compose up -d &&
docker-compose run app composer install &&
docker-compose run app php bin/console doctrine:migrations:migrate -n &&
docker-compose run app php bin/console doctrine:fixtures:load -n &&
docker-compose down -v
