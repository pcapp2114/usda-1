#!/bin/bash

# This file is merely to give a warning about the sync script and exit if you don't want to run it

# Throw warning to make sure user is aware of the risk
echo -e "\033[101m\033[97m\n
                                                                       
  WARNING: This script will pull down the most updated database and    
  files and import them into your local site.                          
                                                                       
  Any settings or configuration that are not saved will be lost.       
                                                                       
  Are you sure you want to continue?                                   
                                                                       
                                                                       \n\033[0m";
select yn in "Yes" "No"; do
  case $yn in
    Yes ) break;;
    No ) echo "Exiting sync script..." && sleep 2 && kill 0 >/dev/null;
  esac
done