#! /bin/bash

# This script will prompt you to delete/remove your entire installation and set it up from scratch. 

# Set the script to exit if there are any errors at all.
set -e;

echo -e "\033[101m\033[97m
                                                             
  WARNING: You are about to stop your Drupal environment     
  and remove it's containers.                                
                                                             
  Work could be lost in the process if it is not backedup.   
                                                             
  Are you sure you want to continue?                         
                                                             \033[0m\n";

select yn in "Yes" "No"; do
  case $yn in
    Yes ) break;;
    No ) echo "Exiting make clean script..." && sleep 2 && kill 0 >/dev/null;
  esac
done

# Stop environment images
#docker stop ${PWD##*/}_php_1
#docker stop ${PWD##*/}_mysql_1

# Removing environment containers
#docker rm ${PWD##*/}_php_1
#docker rm ${PWD##*/}_mysql_1

if [ "$( docker container inspect -f '{{.State.Running}}' nass_website )" == "true" ]; then
  # Stop environment images
  docker stop nass_website
  # Removing environment containers
  docker rm nass_website
  echo -e "\n\033[1;93mDocker container nass_website removed!\033[0m\n"
fi

if [ "$( docker container inspect -f '{{.State.Running}}' nass_mariadb )" == "true" ]; then
  # Stop database image
  docker stop nass_mariadb
  # Removing database containers
  docker rm nass_mariadb
  echo -e "\n\033[1;93mDocker container nass_mariadb removed!\033[0m\n"
fi

# Remove all unused docker containers, images, networks, volumes that are killed or stopped
# WARNING - Using these could delete other projects and information for other local sites.
# docker container prune -f
# docker image prune -f
# docker network prune -f
# docker volume prune -f
# docker system prune -f