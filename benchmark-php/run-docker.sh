#!/usr/bin/env bash


# ------------------
# Init vars
# ------------------
version="7.1"
result="-r csv"
outer=
inner=
format=


# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-o | --outer )		
			shift
			outer="-o $1"
			;;
		-i | --inner )		
			shift
			inner="-i $1"
			;;
		-f | --format )  			
			shift  
			format="-f $1"
			;;
		-r | --result )  				
			shift
			result="-r $1"
			;;
		-v | --version )  				
			shift
			version="$1"
			;;
		* )                     	
			echo "Unknown argument $1"
			exit 1
    	esac
	shift
done


# ------------------
# Run docker
# ------------------:"$version"-cli
docker run --rm -it -v "$PWD:/opt/benchmark" -w /opt/benchmark benchmark-php \
	sh -c " 
		composer install && \
		composer update && \
		php init.php b:r $result $outer $inner $format -d ./"

