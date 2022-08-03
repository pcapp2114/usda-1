#!/bin/bash
# Bash Menu Script Example

# Set the script to exit if there are any errors at all.
set -e;

# Ask if AWS sync needs to be skipped
echo -e "\033[101m\033[97m\n
                                                                       
  You can sync the files directory for your site.
  
  You may use a local backup of the files. If you do not have
  a local copy of the files backup available, you can skip this step.

  Please select a files backup option below:
                                                                       \n\033[0m\n"; 

PS3='Please enter your choice: '
options=("Use a local files copy" "Skip")
select opt in "${options[@]}"
do
    case $opt in
        "Use a local files copy")
            echo -e "\033[1;93mYou chose to import a local files copy.\033[0m\n";
            source ./scripts/local_files.sh;
            ;;
        "Skip")
            break
            ;;
        *) echo "invalid option $REPLY";;
    esac
done