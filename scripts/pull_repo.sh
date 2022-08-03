#!/bin/bash

HHS_GIT_REPO=$(grep HHS_GIT_REPO .env | xargs)
IFS='=' read -ra HHS_GIT_REPO <<< "$HHS_GIT_REPO"
HHS_GIT_REPO=${HHS_GIT_REPO[1]}

DRUPAL_REPO=$(grep DRUPAL_REPO .env | xargs)
IFS='=' read -ra DRUPAL_REPO <<< "$DRUPAL_REPO"
DRUPAL_REPO=${DRUPAL_REPO[1]}

DRUPAL_VERSION=$(grep DRUPAL_VERSION .env | xargs)
IFS='=' read -ra DRUPAL_VERSION <<< "$DRUPAL_VERSION"
DRUPAL_VERSION=${DRUPAL_VERSION[1]}

#PS3='Is this a new installation or currrent HHS site?'
echo -e "\n\033[1;93mThe site will be setup using the current directory and codebase. Is this correct?\033[0m\n"
options=("Yes. Continue Installation" "Quit")
select opt in "${options[@]}"
do
    case $opt in
        "Yes. Continue Installation")
            break
            ;;
        "Quit")
            kill 0 >/dev/null
            ;;
        *) echo "invalid option $REPLY";;
    esac
done