#! /bin/bash
FILE=composer.lock
if test -f "$FILE"; then
    echo -e "\033[1;93mRemoving composer.lock.\033[0m\n";
    rm composer.lock;
    echo -e "\033[1;93mRunning composer install...\033[0m\n";
fi