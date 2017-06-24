#!/usr/bin/env bash

# ------------------
# Init vars
# ------------------
languageOptions=("php" "java" "nodejs")
language=
versionPHP="7.1"
versionJAVA="8"
versionNodeJS="7.7"


# ------------------
# Print error messages
# ------------------
function error () {
	printf "\n\tError: $1\n\n"
	exit 1
}


# ------------------
# Print help
# ------------------
function printHelp () {
	printf "\nScript to build docker images for benchmarks.\n"
	printf "If you wish to build images with different language version, just create Dockerfile inside benchmark-{LANGUAGE}/docker/v{VERSION}/Dockerfile and then change version variables in this file, section #Init vars.\n"
	printf "\n\nOptions:\n"
	printf "\t-h, --help:\t\t Print this very help.\n"
	printf "\t-l, --language <s>:\t Build image for specific benchmark only (php, java, nodejs).\n\n"
	exit 0
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
		docker build -t darkling/benchmark-php:"$versionPHP" -f $PWD/benchmark-php/docker/v"$versionPHP"/Dockerfile $PWD/benchmark-php
	fi

	# java
	if [[ "$language" == "java" || "$language" == "" ]]
	then
		docker build -t darkling/benchmark-java:"$versionJAVA" -f $PWD/benchmark-java/docker/v"$versionJAVA"/Dockerfile $PWD/benchmark-java
	fi

	# nodejs
	if [[ "$language" == "nodejs" || "$language" == "" ]]
	then
		docker build -t darkling/benchmark-nodejs:"$versionNodeJS" -f $PWD/benchmark-nodejs/docker/v"$versionNodeJS"/Dockerfile $PWD/benchmark-nodejs
	fi

    printf "\n\tDocker images were successfully built.\n\n"
}


# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-h | --help )
			shift
			printHelp
			;;
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