#!/bin/bash
# Bash Menu Script Example

# Set the script to exit if there are any errors at all.
set -e;

# Ask if AWS sync needs to be skipped
echo -e "\033[101m\033[97m\n
                                                                       
  You are able to import databases into this environment. 
  
  If you do not have a local database backup available, you can skip this step.

  Please select a database sync option below:
                                                                       \n\033[0m\n"; 

PS3='Please enter your choice: '
options=("Use a local database" "Skip")
select opt in "${options[@]}"
do
    case $opt in
        "Use a local database")
            echo -e "\033[1;93mYou chose to import a local database.\033[0m\n";
            source ./scripts/local_database.sh;
            ;;
        "Skip")
            break
            ;;
        *) echo "invalid option $REPLY";;
    esac
done