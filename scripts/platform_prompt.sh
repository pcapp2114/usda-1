#!/bin/bash

# This script prompts the user to select their platform, then saves the choice into .tmp/platform.txt
set -e

# temp working dir
TEMP_DIR=".tmp"
PLATFORM_FILE="$TEMP_DIR/platform.txt"
mkdir -p $TEMP_DIR

# prompt user
echo -e "\n\033[1;198m  Enter the number that corresponds with your local platform:\033[0m";

# capture user input
select which_platform in "macosx" "windows"; do
    echo -e "\nPlatform is \033[1;198m$which_platform\033[0m.";
    echo "$which_platform" > $PLATFORM_FILE;
    break;
done
