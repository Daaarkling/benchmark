#!/usr/bin/env bash


# ------------------
# Set default options
# ------------------
version="7.5"
outer=30
inner=100
result="csv"
format=


# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-o | --outer )		
			shift
			outer="$1"
			;;
		-i | --inner )		
			shift
			inner="$1"
			;;
		-f | --format )  			
			shift  
			format="$1"
			;;
		-r | --result )  				
			shift
			result="$1"
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
# ------------------
docker run --rm -it -v "$PWD:/opt/benchmark" -w /opt/benchmark node:"$version" \
	sh -c "
		npm rebuild && \
		node init.js -r $result -f $format -o $outer -i $inner -d ./"
