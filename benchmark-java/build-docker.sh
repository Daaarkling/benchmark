#!/usr/bin/env bash

# ------------------
# Init vars
# ------------------
version="8"


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
	printf "\nScript to build docker image for JAVA benchmark.\nYou can create your own images, just simply create Dockerfile in docker/v{VERSION}/Dockerfile."
	printf "\n\nOptions:\n"
	printf "\t-h, --help:\t\t Print this very help.\n"
	printf "\t-v, --version <s>:\t Build docker image darkling/benchmark-java:{VERSION}, directory with version name must exist in docker dir and contain Dockerfile.\n\n"
	exit 0
}


# ------------------
# Check given version
# ------------------
function setVersion () {
	if [ -f "${PWD}/docker/v${1}/Dockerfile" ]
	then
		version=$1
		return
	fi

	error "Dockerfile not found in v$1"
}


# ------------------
# Build image
# ------------------
function run () {

	docker build -t darkling/benchmark-java:"$version" -f $PWD/docker/v"$version"/Dockerfile .
    printf "\n\tDocker image was successfully built.\n\n"
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
		-v | --version )
			shift
			setVersion $1
			;;
		* )
			error "Unknown option $1"
    	esac
	shift
done

run