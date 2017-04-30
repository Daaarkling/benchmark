#!/usr/bin/env bash

#vars
languageOptions=("php" "java" "nodejs")
language=


# ------------------
# Print error messages
# ------------------
function error () {
	echo ""
	echo "	Error: $1"
	echo ""
	exit 1
}


# ------------------
# Check given language option
# ------------------
function setLanguage () {
	languageString=
	for i in ${languageOptions[@]}
	do
		if [ "$i" = "$1" ]
		then
			language=$1
			return
		fi
		languageString="$languageString $i"
	done
	error "Language must be one of these options: $languageString"
}


# ------------------
# Build images
# ------------------
function run () {
    if [[ "$(which docker)" == "" ]]
    then
        error "Docker command was not found. Please install it (https://www.docker.com)"
    fi

    # php
	if [[ "$language" == "php" || "$language" == "" ]]
	then
		docker build -t darkling/benchmark-php:7.1 $PWD/benchmark-php/docker/v7.1
	fi

	# java
	if [[ "$language" == "java" || "$language" == "" ]]
	then
		docker build -t darkling/benchmark-java:8 $PWD/benchmark-java/docker/v8
	fi

	# nodejs
	if [[ "$language" == "nodejs" || "$language" == "" ]]
	then
		docker build -t darkling/benchmark-nodejs:7.7 $PWD/benchmark-nodejs/docker/v7.7
	fi

    echo ""
    echo "Docker images were successfully built."
    echo ""
}


# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-l | --language )
			shift
			setLanguage $1
			;;
		* )
			error "Unknown option $1"
    	esac
	shift
done

run
