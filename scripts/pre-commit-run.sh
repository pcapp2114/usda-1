#! /bin/bash

# This script detects if files have been staged for commiting and, if so, runs precommit on those staged files.
#set -e
git diff --quiet --exit-code --cached

check=$?
if [ $check -ne 0 ]; then
  echo -e "\nStaged files detected. Running pre-commit hooks...\n";
  cp ./.pre-commit-config.yaml docroot
  cd docroot
  pre-commit --version
  pre-commit install
  pre-commit run --all-files
  rm ./.pre-commit-config.yaml
else
  echo -e "\nPlease stage some files for committing.\n";
fi
