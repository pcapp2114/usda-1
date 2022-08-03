#! /bin/bash

# This script is merely a prompt to explain what the make file is about to do
set -e

# Throw warning to make sure user is aware of the risk
echo -e "\033[104m\033[97m

  This makefile is engineered to setup the NASS Drupal website
  locally on your machine. In order to setup the environment successfully,
  the following software is required:

    * Docker - https://docs.docker.com/get-docker
    * Composer - https://getcomposer.org/download
    * Node.js - https://nodejs.org/en/download
    * Pre-commit - https://pre-commit.com
    * Drush - https://github.com/drush-ops/drush-launcher

  There will be a check to make sure you have the required software to
  successfully set up the environment.
                                                                                  \033[0m";
echo -e "\n\033[1;198m  Does your machine meet all of these requirements?\033[0m";

select yn in "Yes" "No"; do
  case $yn in
    Yes ) break;;
    No ) echo -e "\nExiting environment build..." && sleep 2 && kill 0 >/dev/null;
  esac
done
