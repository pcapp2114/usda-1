#! /bin/bash

# Check if docker containers are healthy before proceeding
if docker ps | grep -q nass_website
then 

  # Get the port of the apache container
  host=$(docker port nass_website | grep -o '[^:]\+$' | head -1 );

  echo -e "\n\033[1;93mClearing Drupal Cache...\033[0m";
  drush cr;

  # Output the host
  echo -e "\n\033[1;32mYour installation is now avaiable at: http://localhost:$host\033[0m";

else
  echo "The docker container is not running!";
  echo "Try restarting docker.";
  exit 1;
fi