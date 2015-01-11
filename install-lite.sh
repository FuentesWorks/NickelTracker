#!/bin/bash

## Assuming a basic LAMP stack running on Ubuntu

chmod +x install-full.sh
chmod +x install-lite.sh
chmod +x update.sh

echo -e "\e[1m--- Install Symfony on NickelTracker ---\e[0m"
echo - Download Composer -
curl -sS https://getcomposer.org/installer | php
php composer.phar config --global discard-changes true

echo - Install Symfony -
php composer.phar install

echo - Clean up installation -
chmod -R 777 app/cache
chmod -R 777 app/logs
chmod -R 777 app/config/parameters.yml

echo -e "\e[1mAll done.\e[0m"
