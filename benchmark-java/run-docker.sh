#!/usr/bin/env bash


# ------------------
# Init vars
# ------------------
version="8"
result="-r csv"
chatty=
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
# ------------------:"$version"-cli
docker run --rm -it -v "$PWD:/opt/benchmark" -w /opt/benchmark darkling/benchmark-java:"$version" \
	sh -c " 
		mvn package && \
		java -jar target/benchmark-java-1.0-jar-with-dependencies.jar $result $outer $inner $format $chatty -d ./"

