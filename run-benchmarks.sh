# ------------------
# Init variables
# ------------------
outputOptions=("csv" "file" "console")
output=${outputOptions[0]}
formatOptions=("native" "json" "xml" "protobuf" "msgpack" "avro")
format=
repetitions=100
outputDir="./"
testDataOptions=("small" "big")
testData=$(readlink -f "./testdata/test_data_${testDataOptions[0]}.json")



# ------------------
# Validation
# ------------------
function setOutput () {
	optionsString=
	for i in ${outputOptions[@]}
	do
		if [ "$i" = "$1" ]
		then
			output=$1
			return	
		fi
		optionsString="$optionsString $i"
	done
	error "Output must be one of these options: $optionsString"
}

function setOutputDir () {
	if [ -d "$1" ] && [ -w "$1" ]
	then
		outputDir=$1
	else
		error "Output path is not a directory or is not writable."
	fi
}

function setFormat () {
	formatString=
	for i in ${formatOptions[@]}
	do
		if [ "$i" = "$1" ]
		then
			format=$1
			return	
		fi
		formatString="$formatString $i"
	done
	error "Format must be one of these options: $formatString"
}

function setData () {
	testDataString=
	for i in ${testDataOptions[@]}
	do
		if [ "$i" = "$1" ]
		then
			testData=$(readlink -f "./testdata/test_data_$1.json")
			if [ -f "$testData" ] && [ -r "$testData" ]
			then
				return
			else
				error "Test data file $testData not found."
			fi
		fi
		testDataString="$testDataString $i"
	done
	error "Test data must be one of these options: $testDataString"
}

function setRepetitions () {
	if [ $1 -gt 0 ]
	then
		repetitions=$1
	else
		error "Repetitions must be greater then zero."
	fi
}

# ------------------
# Print
# ------------------
function error () {
	echo "Error: $1"
	exit 1
}

function printHelp () {
	echo "TODO"
	exit 0
}

# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-r | --repetitions )           	shift
						setRepetitions $1
                                		;;
		-o | --output )  		shift  
						setOutput $1
	  		                      	;;
		-od | --out_dir )  		shift  
						setOutputDir $1
		                        	;;
		-f | --format )  			shift  
						setFormat $1
		                        	;;
		-d | --data )  			shift  
						setData $1
		                        	;;
		-h | --help )           	printHelp	
		                        	;;
		* )                     	error "Unknown argument $1"
    	esac
	shift
done

# ------------------
# Run benchmarks
# ------------------
function runBenchmarks () {
	if [ "$format" != "" ]
	then
		php ./benchmark-php/init.php b:r -r "$repetitions" -o "$output" --out_dir="$outputDir" -f "$format" -d "$testData" > /dev/null
		java -jar benchmark-java/target/benchmark-java-1.0-jar-with-dependencies.jar-r "$repetitions" -o "$output" -od "$outputDir" -f "$format" -d "$testData" > /dev/null
	else
		php ./benchmark-php/init.php b:r -r "$repetitions" -o "$output" --out_dir="$outputDir" -d "$testData" > /dev/null
		java -jar benchmark-java/target/benchmark-java-1.0-jar-with-dependencies.jar -r "$repetitions" -o "$output" -od "$outputDir" -d "$testData" > /dev/null
	fi
}


# ------------------
# Plot graphs
# ------------------
function plot () {
	
	phpCsv="php-benchmark-output.csv"
	javaCsv="java-benchmark-output.csv"
	
	if [ -f "$outDir$phpCsv" ] && [ -r "$outDir$phpCsv" ] && [ -f "$outDir$javaCsv" ] && [ -r "$outDir$javaCsv" ]
	then
		# create temp file and write into it combined result of benchmarks
		temp=$(mktemp /tmp/benchmark-combined.csv.XXXXXX)
		$(cat php-benchmark-output.csv <(tail -n+2 java-benchmark-output.csv) > $temp)
		
		gnuplot -persist <<-EOFMarker
			
			set datafile separator ";"			# csv separator
			set style fill solid 0.8 border -1	# style of bar chart
			set boxwidth 0.5 relative			# style of bar chart
			set ylabel "Time (ms)"				# label of y
			set grid y							# show horizontal lines
			set logscale y						# logaritmics scale
			set xtics right rotate by 45		# rotate names of libs by 45 deg
			set key autotitle columnhead 		# skip first line in csv	
			unset key							# no title for data
			set lmargin 15						# margin so names could fit
			set title "Serialize"				# title of graph
			plot "$temp" using 2:xtic(1) with boxes, \
				 "" using 0:2:2 with labels
		EOFMarker
		
		$(rm $temp)		# remove temp file
	fi
}

echo "$output"
echo "$outputDir"
echo "$repetitions"
echo "$format"
echo "$testData"


runBenchmarks
plot
echo "Benchmark run successfully!"


exit 0
