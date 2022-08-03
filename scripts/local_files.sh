#! /bin/bash

# Directory where the local databases are forr this project
DIR=backups/files
echo -e "\n\033[1;93mSelect file to import, or type 0 to exit: \033[0m\n";
PS3='Select file to import, or type 0 to exit: '

# Look for all files iin the .tar.gz or .zip format
select file in $( find ${DIR} -maxdepth 1 -type f \( -iname \*.tar.gz -o -iname \*.zip \)); do
    if [[ $REPLY == "0" ]]; then
        echo -e "\n\033[1;93mExiting environment setup.\033[0m\n" >&2
        exit
    elif [[ -z $file ]]; then
        echo -e "\n\033[1;93mInvalid choice. Pleease try again.\033[0m\n" >&2
    else
        echo -e "\n\033[1;93mImporting files into Drupal container nass_website...\033[0m\n" >&2
        if [[ $file == *.tar.gz ]]; then
          mkdir ./local/files/tmp;
          tar -zxf $file -C ./local/files/tmp;
          mv ./local/files/tmp/mnt/public-content/* ./local/files
          rm -rf ./local/files/tmp/;
          echo -e "\033[1;32mFiles imported!\033[0m\n"
        elif [[ $file == *.zip ]]; then
          mkdir ./local/files/tmp;
          unzip -qq $file -d ./local/files/tmp;
          rm -rf ./local/files/tmp/__MACOSX;
          mv ./local/files/tmp/mnt/public-content/* ./local/files
          rm -rf ./local/files/tmp/;
          echo -e "\033[1;32mFiles imported!\033[0m\n"
        fi
        exit;
    fi
done