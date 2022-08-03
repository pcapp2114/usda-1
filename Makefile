init: forward_warning platform_prompt timestamp_start docker_check drush_check composer_check nodejs_check precommit_check reconcile_dep pull_repo docker composer first_sync host timestamp_end

##
## ------------------------- Docker Drupal Environment -------------------------
## This makefile is used to standup a Docker environment for Drupal development.
## After it runs, you should have working Drupal site running locally.
## To begin the makefile process, simply type 'make init' at the root of this project.
## The following targets below explain additional commands that can be used.
##

##init:  Create development environment

help: ## Show all commands in this make file
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

forward_warning: # Issues a warning of the requirements needed for this environment
	@./scripts/forward_warning.sh;

platform_prompt: # Prompts user for OS
	@./scripts/platform_prompt.sh;

timestamp_start: # Creates timestamp for beginning and end of script
	@echo "\n\033[1;93mBuild started at:\033[0m"; \
	./scripts/timestamp.sh; \
	echo ""

timestamp_end: # Creates timestamp for beginning and end of script
	@echo "\n\033[1;93mBuild finished at:\033[0m"; \
	./scripts/timestamp.sh; \
	echo ""

host: # Echos host and port of your installation after Docker is setup
	@echo "\n"; \
	./scripts/host.sh;

docker_check: # Checks if Docker is installed
	@echo "Checking for \033[1;198mDocker...\033[0m"; \
	if ! command -v docker &> /dev/null; then \
		./scripts/tally_dependency.sh docker; \
	else \
		echo "\033[1;32mInstalled\033[0m\n"; \
	fi;

drush_check: # Checks if Drush is installed
	@echo "Checking for \033[1;198mDrush...\033[0m"; \
	if ! command -v drush &> /dev/null; then \
		./scripts/tally_dependency.sh docker; \
	else \
		echo "\033[1;32mInstalled\033[0m\n"; \
	fi;

composer_check: # Checks if composer is installed and runs if it is.
	@echo "Checking for \033[1;198mComposer...\033[0m"; \
	if ! command -v composer &> /dev/null; then \
		./scripts/tally_dependency.sh composer; \
	else \
		echo "\033[1;32mInstalled\033[0m\n"; \
	fi; \

nodejs_check: # Checks if Node.js is installed
	@echo "Checking for \033[1;198mNode.js...\033[0m"; \
	if ! command -v node &> /dev/null; then \
		./scripts/tally_dependency.sh nodejs; \
	else \
		echo "\033[1;32mInstalled\033[0m\n"; \
	fi;

precommit_check: # Checks if Pre-Commit is installed
	@echo "Checking for \033[1;198mPre-Commit...\033[0m"; \
	if ! command -v pre-commit &> /dev/null; then \
		./scripts/tally_dependency.sh precommit; \
	else \
		echo "\033[1;32mInstalled\033[0m\n"; \
	fi;

reconcile_dep: # Checks if any dependencies are missing and halts Makefile if so
	@test -s ".tmp/missing_dependencies.txt" && { echo "\033[1;198mSummary of missing dependencies:\033[0m"; ./scripts/tally_dependency.sh print; exit 1; } || echo "\033[1;32mAll dependencies are present!\033[0m\n";

pull_repo:
	@./scripts/pull_repo.sh;

docker: ## Runs docker-compose up -d
	@./scripts/docker.sh;

composer: ## Runs composer update
	@./scripts/composer-prepare.sh; \
	composer install --no-interaction --ignore-platform-reqs;

first_sync:
	@./scripts/settingsfile.sh; \
	./scripts/database_select.sh;
	./scripts/files_sync.sh;

database: ## Import database
	@./scripts/database_select.sh; \

drush:
	@./scripts/drush.sh $(filter-out $@,$(MAKECMDGOALS)); \

%:
  @:

sync: ## Imports database and files
	@./scripts/database_select.sh;
	./scripts/files_sync.sh;

files:
	@./scripts/files_sync.sh;	

stop: ## Stops docker containers
	@./scripts/stop.sh; \

precommit: ## Runs pre-commit hooks without commiting. Files must be staged.
	@./scripts/pre-commit-run.sh

start: ## Starts your environment
	@./scripts/start.sh