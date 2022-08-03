#!/bin/bash

# This script will tally a list of missing dependencies into a temporary file.

usage() { # usage statement
cat << EOF

usage: bash ./scripts/tally_dependency.sh [dependency]
  [dependency]   (Required)    --> Dependency to be tallied into running list

  Dependency options:
    tally_dependency.sh (aws-cli|aws-vault|docker|composer|nodejs|precommit)

  Reconcile:
    tally_dependency.sh print  --> Prints running list of missing dependencies to screen
EOF
}

THE_DEPENDENCY=	# declare dependency var
TEMP_DIR=".tmp"	# temp working dir
MISSING_DEP_TXT="$TEMP_DIR/missing_dependencies.txt"

# map arguments to variables
while [ "$1" != "" ]; do
    case $1 in
        docker | composer | nodejs | precommit | drush | print )
            THE_DEPENDENCY=$1
        	;;
        * )
			usage
            exit 1
			;;
    esac
	shift
done

[ -z $THE_DEPENDENCY ] && usage && exit

# Define the user-friendly message for each missing dependency
case $THE_DEPENDENCY in
	aws-cli )
		DEP_NAME="AWS CLI"
		DEP_URL="https://aws.amazon.com/cli"
		;;
	aws-vault )
		DEP_NAME="AWS Vault"
		DEP_URL="https://github.com/99designs/aws-vault"
		;;
	docker )
		DEP_NAME="Docker"
		DEP_URL="https://docs.docker.com/get-docker"
		;;
	composer )
		DEP_NAME="Composer"
		DEP_URL="https://getcomposer.org/download"
		;;
	nodejs )
		DEP_NAME="Node.js"
		DEP_URL="https://nodejs.org/en/download"
		;;
	precommit )
		DEP_NAME="Pre-Commit"
		DEP_URL="https://pre-commit.com"
		;;
  drush )
		DEP_NAME="Drush"
		DEP_URL="https://github.com/drush-ops/drush-launcher"
		;;
	print )
		cat $MISSING_DEP_TXT
		rm $MISSING_DEP_TXT
		exit
		;;
	* )
		;;
esac

# Append the appropriate message to the missing dependencies file.
# If the file does not exist, it is created.
echo -e "\033[1;91mNot found\033[0m\n"
echo -e "\033[0;91mNo $DEP_NAME found. Please go install it: $DEP_URL\033[0m" >> $MISSING_DEP_TXT
