#!/bin/bash
# version 1.2 -2013.44.0

echo Starting update procedure..

echo -e "\e[1m--- Updating files from GitHub ---\e[0m"
git stash
git pull

chmod +x update.sh

echo -e "\e[1m--- Updating: NickelTracker ---\e[0m"

echo - Update packages with composer -
php composer.phar self-update
sudo chmod 777 -R app/cache # bug
php composer.phar update

echo - Fix permissions -
sudo chmod 777 -R app/logs

echo - Clear cache -
sudo chmod 777 -R app/cache
php app/console cache:clear --env=prod
sudo chmod 777 -R app/cache

echo - Update database -
php app/console doctrine:schema:update --force


echo Done.