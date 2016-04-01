#!/bin/bash

SCRIPT_DIR=$(dirname $(readlink -f $0))

ERROR=0
JUST_GRUNT="0"

if [ ! -z "$1" ]
then
    if [ "$1" = "grunt" ]
    then
        JUST_GRUNT="1"
    fi
fi

function runCommand()
{
    if [ -z "$1" ]
    then
        echo
        echo -e "\e[0;31msyntax error\e[0m - no command given"
        echo
        return 1
    else
        CMD="$1"
    fi

    cd $SCRIPT_DIR

    if [ ! -z "$2" ]
    then
        SUB_DIR="$2"
        cd $SCRIPT_DIR/$SUB_DIR
    fi

    echo
    echo
    echo -e "\e[0;32mrunning: $CMD\e[0m"
    echo
    eval $CMD
    if [ "$?" != 0 ]
    then
        ERROR=1
    fi
}

runCommand "chown daemon. cache/ -R" "bootstrap"

runCommand "rm -f compiled.php" "bootstrap/cache"

if [ "$JUST_GRUNT" = "0" ]
then
    runCommand "composer install"
fi

runCommand "npm install" "build/default"

runCommand "grunt build" "build/default"

if [ "$JUST_GRUNT" = "0" ]
then
    runCommand "composer dump"
fi

exit $ERROR
