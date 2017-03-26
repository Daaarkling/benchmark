#!/usr/bin/env bash

if [[ "$(which docker)" == "" ]]
then
	echo ""
	echo "Docker command was not found. Please install it (https://www.docker.com)"
	echo ""
	exit 1
fi

docker build -t darkling/benchmark-php:7.1 $PWD/benchmark-php/docker/v7.1
docker build -t darkling/benchmark-java:8 $PWD/benchmark-java/docker/v8
docker build -t darkling/benchmark-nodejs:7.7 $PWD/benchmark-nodejs/docker/v7.7

echo ""
echo "Docker images were succesfully built."
echo ""
