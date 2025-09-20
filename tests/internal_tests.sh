#!/bin/bash

DOMAIN="https://principia-web.uwu"

echo "== Testing /internal/get_featured =="
curl -ik "$DOMAIN/internal/get_featured"

echo "== Testing /internal/get_level =="
curl -ik $DOMAIN/internal/get_level?i=1

echo "== Testing /internal/login =="
curl -ik --data 'key=cuddles&username=ROllerozxa&password=password' $DOMAIN/internal/login

echo "== Testing /internal/register =="
curl -ik --data 'key=cuddles&username=ROllerozxa23&password=password&email=lol@haha.yes' $DOMAIN/internal/register

echo "== Testing /internal/version_code =="
curl -ik $DOMAIN/internal/version_code
