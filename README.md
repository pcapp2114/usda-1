# **Local Drupal Docker Environment**

This is a docker stack environment running a PHP and MariaDB container. By running the contained make file, a Drupal website will be stood up locally and prepped for further development.

Once completed, this will give you all necessary tools to begin development.

## **Table of Contents**
* [Useful Commands](#1)
* [Quick Start](#2)
* [Prerequisites](#3)
* [What this environment does](#4)
* [Before you install](#5)
* [Installation](#6)
* [Docker Containers](#7)
* [Localhost and Port](#8)
* [Composer](#9)
* [Pre-Commit Hooks](#12)
* [Drush](#13)
* [Create A New Drupal User](#14)
* [Connecting to the database](#15)
* [Environment username and password](#16)
* [Using Git with this project](#17)
* [Setting up other projects](#18)
* [Removing](#19)
* [Troubleshooting](#20)

## **Useful Commands**<a name="1"></a>

  * `make` - This is the initial command used to create your local environment and pull the site you wish to use with this environment. This should only be used once to download the docker containers, check the software requirements, and build your inital repo for teh project.

  * `make start` - Once you have your repo and site built, you can always use this to start the site if you've restarted your machine.

  * `make help` - This will list all commands in the script and what they are used for.

  * `make docker` - This will run `docker-compose up -d`

  * `make database` - Imports a local database

  * `make sync` - This will pull the most recent production database from AWS and imports it. It will then download the most recent Drupal files from AWS and imports it into your local environment.

  * `make files` - Imports local files backup.

  * `make stop` - This will stop the docker containers and Drupal file system.

  * `make precommit` - This command detects if files have been staged for commiting and, if so, runs precommit on those staged files.

## **Quick start**<a name="2"></a>
* Use `git clone -b develop ssh://git@bitbucket.org/usdawcmaas/nass_www.git nass` to pull the code for this environment.
* See the `.env` file at the root of the project and set your configuration
* If you have a backup of the database and files, pleease put them in the `backups` directory at the root of the project.
* If building the site and environment for the first time, run `make`
* If building the site from a repo that has already been established with this environment, run `make start`
* Follow the prompts to either upload a database and files or skip that step
* The final process of the script will let you know where you can view your site once it is ready.

## **Prerequisites**<a name="3"></a>
Before attempting to use this environment, you must make sure you have the following installed. The Makefile will check if these are installed and will exit if they are not with a prompt to install before continuing.

All systems require the following (follow the links to install each tool):

* [Git](https://git-scm.com/downloads)
* [Docker](https://www.docker.com/get-started)
* [Composer](https://getcomposer.org/download/)
* [Node.js](https://nodejs.org/en/download)
* [Pre-Commit](https://pre-commit.com)
* [PHP](https://www.php.net/manual/en/install.php)
* [Drush](https://github.com/drush-ops/drush-launcher)

If this is a new installation from scratch, you will have the option to install an empty site.

Windows users should use Git Bash to execute commands. VSCode is the IDE of choice. See the troubleshooting area for specific Windows Troubleshooting tips.

## **What this environment does**<a name="4"></a>
The scripts installation process can do a number of things:

-  Checks to make sure required software is installed
-  Prompt you to download required software if it is not detected
-  Download docker containers
-  Allow the most recent files backup to be imported
-  Provide a way to import a database locally
-  Runs pre-commit hooks against staged files that are being committed.

Ideally, the script takes between 5-60 mins to run depending on your internet connection speed and your machine's available resources.

## **Before You Install**<a name="5"></a>
Please see the `.env` file at the base of your project to set the port you want to use.

## **Installation**<a name="6"></a>
Pull down the latest files from the repository using git. Cloning into a directory is the easiest way to do this:

```git clone -b develop ssh://git@bitbucket.org:usdawcmaas/nass_www.git nass```

In this example `nass` is the name of your project and you are cloning from the branch `develop`.

Run the following command from the root of the project directory to begin installation:

```make```

## **Docker Containers<a name="7"></a>
Once the script has finished running, you will see both of the docker containers running at the very end:

```bash
Creating nass_website ... done
Creating nass_mariadb ... done
```

If you run `docker ps` you will see a listing of your containers that were just setup:
```bash
CONTAINER ID   IMAGE                       COMMAND                  CREATED       STATUS       PORTS                                            NAMES
afcbf6135a72   mariadb:10.6                "docker-entrypoint.s…"   2 hours ago   Up 2 hours   0.0.0.0:3306->3306/tcp, :::3306->3306/tcp        nass_mariadb
6d83b738d7aa   ewaps:php81                 "/run-httpd.sh"          2 hours ago   Up 2 hours   443/tcp, 0.0.0.0:8077->80/tcp, :::8077->80/tcp   nass_website
```

## **Localhost and Port**<a name="8"></a>

Your project site will always be at the host `localhost` or `0.0.0.0`.

If you look in the `.env` file, you will see a place to change the port of your site to your preference:
``` ENV_PORT=<SET PORT NUMBER> ```

_Note: Set the port number and save the file before running the make command._

## **Composer**<a name="9"></a>

Drupal is managed wih Composer, a PHP package dependency management system. These packages are primarily stored in Packagist.org. This will setup automatically with your environment setup and can be found one port number lower than your site.

Example: Your Drupal installation is found at `localhost:8734`.

Upon creation this will be added into your main composer.json file in the root of your project.

## **Pre-Commit Hooks**<a name="12"></a>
Git hooks are useful scripts that can help identify problems with the files you are committing such as being too large a file, improperly formatted, or trailing whitespace. Pre-commit allows for a simple way to add additional test into your project. There are many already developed pre-commit hooks that can easily be added and tailored to your needs.

Within this project we have added the following Pre-Commit hooks:

  * Yaml file format checker
  * End of file fixer
  * Trailing whitespace fixer
  * Large file check set to 1200kb but can be adjusted
  * Check for any merge conflicts
  * Detect that the user has AWS credentials created locally
  * Do not allow commits to `staging`,`master`, or `main` git branches
  * PHP Linting
  * PHP Unit Test Runner (requires test to be setup)

These hooks will run for each git commmit made prior to the code actually being committed. It's also possible to run these hooks prior to commiting code by using the make command `make precommit`. All that is nessecary is for files to be staged for commiting in the `docroot` directory of of your project using `git add`. The you can run `make precommit` at the root of the project to run your precommit hooks.

You can make your own hooks for many uses in your project. At the root of your project, you will find `.pre-commit-config.yaml`. This file contains all of the pre-commit repos and hooks that are currently enabled. When `make precommit` runs, this yaml file is copied into the `docroot` and all precommit hooks are executed. After the report is given, the yaml file is removed so as to not add an additional file into the file system. If you prefer, you can always install pre-commit into the `docroot` and have it live in the repository for the site along with it's filesystem rather than it being part of the environment. In that case, `make precommit` would not run properly, and all pre-commit commands would need to be executed directly in the `docroot` directory.

Following the guide, found here[https://pre-commit.com/#new-hooks], it's possible to create hooks fitting any possible situation for your project. An example would be adding a linter for SASS, or perhaps a hook that scans for the location of a particular file type such as .sql or .docx.

## **Drush**<a name="13"></a>
Drush is a Drupal CLI tool that can simplify development and allow you to run helpful commands. This environment comes with Drush installed in the PHP/Apache container.

This command is the prefix for all drush commands. This allows all local development to interface with Drush without having to install it locally, which can sometime be problematic.

```docker exec -it nass_website drush <command>```

In this example `project-name` is the project name.

An example command using this method is `docker exec nass_website drush cr` to rebuild the cache.

Developers may want to create an alias to trigger the main Drush command for simplicity. The alias may be stored in your .bashrc file or .zshrc file.

First determine which shell you are using by typing:

```echo "$SHELL"```

This will usually return either `/bin/bash` or `/bin/zsh`. Depending on the shell you are using, you can add a new alias to make Drush commands easier.

If you're using Zsh, you'll need to create a .zshrc file by typing `touch ~/.zshrc` then `vi ~/.zshrc` to edit the file. If you're using Bash, you can go directly to your bashrc file by typing `vi ~/.bashrc`

Next enter the alias below and save the file:

```alias docker-drush="docker exec nass_website drush" ```

Lastly, you will need to source the alias file by typing `. ~/.bashrc` or `. ~/.zshrc` depending on your shell.

After the alias is entered and sourced, to run Drush commands within the container from anywhere in your project folder you can type `docker-drush` and then your command. For example, if you want to do a cache rebuild within the site running locally, you would type `docker-drush cr`.

Using the `docker-drush` alias is not nesscary within other environments as you should be executing those commands directly inside the container. This alias is merely a local convenience to avoid having to SSH into the docker container to continually run Drush commands.

The container name will change per project. This also means that alias `docker-drush` will need to be different per project. If you were running several projects, you would need to come up with a different drush alias per project. For example:

```
alias nass-drush="docker exec nass_website drush"
```

The example above shows three projects using three different PHP containers. There is now a convenient way to run Drush in all three projects now without having to install Drush locally.

## **Create A New Drupal User**<a name="14"></a>

_Note: If you have been given an account in the production environment or in the database that you've been given, this step is not necessary and you should be able to login with your username and password._

Once your project is created locally, you will need to create a new user and assign a role to be ale to login. You can do this with the following command
```docker exec -it nass_website drush user:create newuser --mail="person@example.com" --password="letmein"```

Take note that `newuser` needs to be changed to your unique username, `person@example.com` needs to be changed to your email address, and `letmein` needs to be a strong password.

Next you will need to assign an admin role to your new account. You can use the following command:
```drush urol "administrator" newuser ```

or if you are using the provided alias:

```docker-drush urol "administrator" newuser ```

## **Connecting to the database**<a name="15"></a>
In the above example, your database for the Drupal installation can be connected to on port 32769. This port will be randomized each time you create an environment using this script. Pay careful attention to make sure you are connecting to the correct database if you have multiple images running.

Use this command to import into the docker mariadb container:

`docker exec -i nass_mariadb mysql -uDB_USERNAME -pDB_PASSWORD DB_NAME < database.sql`

**Note: make sure that `project_name` is correct for the MySQL container**

Please note that the username, password, and database name are not in this command and you must retrieve these from the`.env` file in order to connect tot he database. Additionally, you must make sure that path to database.sql (or whatever the database export is named) is correct in the command.

## **Environment username and password**<a name="16"></a>
The `.env` file located in the root of the project directory contains the database username and passwords. These can be customized to be anything locally but will not be used up stream on other environments.

## **Using Git with this project**<a name="17"></a>
This project uses Git Submodules to manage all of its repositories. There are basically several repos inside of the main repo.

The main repo is the environment repo. Unless you are working on the environment framework, there is no need to commit to this repo or tamper with any of the files. 

The git submodule is inside the `docroot` directory after you complete the environment build. Once you change directories to `docroot` you will then be working inside of that particular sites directory.

This allows you to keep your commits on the environment and Drupal site seperate, but clone them together. Read more about submodules [here](https://git-scm.com/book/en/v2/Git-Tools-Submodules](https://git-scm.com/book/en/v2/Git-Tools-Submodules).


## **Removing**<a name="19"></a>
To remove these containers run the following:

```make stop```

This command will stop and remove your projects docker containers.

To rebuild your docker containers you may type `make docker`. 

## **Troubleshooting**<a name="20"></a>
There are a number of possible errors you may see as you are setting up this environment or trying to start
a website using this environment. 

* `Fatal error: Maximum execution time of 30 seconds exceeded in /var/www/html/vendor/symfony/yaml/Parser.php on line 215`
  - This error can occur when the docker containers are not initialized. We have build in a pause to try to mitigate this, however, at times and depending on the system, docker may need longer to initialize the containers. Please try the containers in about 5 mins and see if they have fully initialized.
