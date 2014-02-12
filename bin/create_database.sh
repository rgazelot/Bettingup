#!/usr/bin/env bash

env=$1

if [ "$env" != "test" ]
then env="dev"
fi

./app/console doctrine:database:drop --env=$env --force $2
./app/console doctrine:database:create --env=$env $2
./app/console doctrine:schema:create --env=$env $2

./app/console doctrine:fixtures:load --env=$env $2 -n
