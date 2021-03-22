#!/bin/bash

docker-compose up -d
sleep 5
docker-compose exec app php bin/console messenger:consume async

