#! /bin/bash

DB_USERNAME=$(grep DB_USERNAME .env | xargs)
IFS='=' read -ra DB_USERNAME <<< "$DB_USERNAME"
DB_USERNAME=${DB_USERNAME[1]}

DB_PASSWORD=$(grep DB_PASSWORD .env | xargs)
IFS='=' read -ra DB_PASSWORD <<< "$DB_PASSWORD"
DB_PASSWORD=${DB_PASSWORD[1]}

DB_NAME=$(grep DB_NAME .env | xargs)
IFS='=' read -ra DB_NAME <<< "$DB_NAME"
DB_NAME=${DB_NAME[1]}

# Directory where the local databases are forr this project
DIR=backups/databases
echo -e "\n\033[1;93mSelect file to import, or type 0 to exit: \033[0m\n";
PS3='Select file to import, or type 0 to exit: '
select file in $( find ${DIR} -maxdepth 1 -type f \( -iname \*.sql.gz -o -iname \*.sql \)); do
    if [[ $REPLY == "0" ]]; then
        echo -e "\n\033[1;93mExiting environment setup.\033[0m\n" >&2
        exit
    elif [[ -z $file ]]; then
        echo -e "\n\033[1;93mInvalid choice. Pleease try again.\033[0m\n" >&2
    else
        echo -e "\n\033[1;93mMaking sure database docker container is stable...\033[0m\n"
        sleep 3;
        until [ "`docker inspect -f {{.State.Running}} nass_mariadb`"=="true" ]; do
          sleep 0.1;
        done;
        echo -e "\n\033[1;93mImporting database into container nass_mariadb...\033[0m\n" >&2
        if [[ $file == *.sql.gz ]]; then
          gunzip < $file | docker exec -i nass_mariadb mysql -u$DB_USERNAME -p$DB_PASSWORD $DB_NAME
          echo -e "\033[1;32mDatabase imported!\033[0m\n"
        else
          docker exec -i nass_mariadb mysql -u$DB_USERNAME -p$DB_PASSWORD $DB_NAME < $file
          echo -e "\033[1;32mDatabase imported!\033[0m\n"
        fi
        exit;
    fi
done