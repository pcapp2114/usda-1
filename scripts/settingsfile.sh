#!/bin/bash

# Copy Drupal settings.php file into place
  rm ./web/sites/default/settings.php;
  echo -e "\033[1;93mSetting up settings.php file.\033[0m\n";
  sudo cp ./scripts/settings.php ./web/sites/default/settings.php;
