#!/usr/bin/env bash


# ------------------
# Init vars
# ------------------
version="7.7"
result="-r csv"
outer=
inner=
format=
chatty=


# ------------------
# Print help
# ------------------
function printHelp () {
	printf "\nScript to run NodeJS benchmark via Docker.\n"
	printf "\n\nOptions:\n"
	printf "\t-h, --help:\t\t Print this very help.\n"
	printf "\t-v, --version <s>:\t Run Docker image darkling/benchmark-nodejs:{VERSION}.\n"
	printf "\t-o, --outer <n>:\t Number of outer repetitions.\n"
	printf "\t-i, --inner <n>:\t Number of inner repetitions.\n"
	printf "\t-f, --format <s>:\t Run benchmark for specific format only.\n"
	printf "\t-r, --result <s>:\t Handle output, you can choose from: csv or console.\n"
	printf "\t-c, --chatty:\t\t Enable verbose (chatty) mode.\n\n"
	exit 0
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
docker run --rm -it -v "$PWD:/opt/output" darkling/benchmark-nodejs:"$version" \
		sh -c "
			node init.js $result $outer $inner $format $chatty -d /opt/output"