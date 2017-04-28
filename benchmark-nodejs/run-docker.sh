#!/usr/bin/env bash


# ------------------
# Init vars
# ------------------
version="7.6"
result="-r csv"
outer=
inner=
format=
chatty=


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
		-c | --chatty )
			shift
			chatty="-c"
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
		npm install && \
		node init.js $result $outer $inner $format $chatty -d ./"

