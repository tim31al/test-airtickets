#!/bin/bash

docker-compose up -d
sleep 8
docker-compose exec app php bin/console messenger:consume async

