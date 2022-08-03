#! /bin/bash

echo -e "Checking to see if a Drush alias can be added...";

if echo $SHELL | grep -q "zsh"; then
  echo -e "You're running zsh. Adding drush alias to your ~/.zshrc file.";
  echo 'alias docker-drush="docker exec nass_website drush"' >> ~/.zshrc;
  exec zsh;

  if grep -q "docker-drush" ~/.zshrc;
  then
    echo -e "Drush alias added! You can now use 'docker-drush' to run drush commands from anywhere in the project."
    echo -e "Example: docker-drush cr"   
  else
    echo -e "Drush alias not found. You may need to manually add this to your bash."
  fi
  sleep 2s;

  echo -e "\n";
elif echo $SHELL | grep -q "bash"; then
  echo -e "You're running bash. Adding drush alias to your ~/.bashrc file.";
  echo 'alias docker-drush="docker exec nass_website drush"' >> ~/.bashrc;
  source ~/.bashrc;

  if grep -q "docker-drush" ~/.bashrc;
  then
    echo -e "Drush alias added! You can now use 'docker-drush' to run drush commands from anywhere in the project."
    echo -e "Example: docker-drush cr"   
  else
    echo -e "Drush alias not found. You may need to manually add this to your bash."
  fi
  sleep 2s;

  echo -e "\n";
else
  echo -e "Not able to add Drush alias.";
  echo -e "\n";  
  echo -e "You can manually add this drush alias into your bashrc file:";
  echo -e "alias docker-drush=\"docker exec nass_website drush"; 
  sleep 4s;
fi
