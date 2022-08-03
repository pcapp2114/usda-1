#!/bin/bash

docker exec -it nass_website drush $1 >/dev/null 2>&1;