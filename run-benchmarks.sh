# ------------------
# Init variables
# ------------------
formatOptions=("native" "json" "xml" "protobuf" "msgpack" "avro")
format=
repetitions=100
outputDir="./"
testDataOptions=("small" "big")
testData=$(readlink -f "./testdata/test_data_${testDataOptions[0]}.json")
combined=
serializeTemp=
deserializeTemp=
sizeTemp=


# ------------------
# Validation
# ------------------
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
		php ./benchmark-php/init.php b:r -o csv -r "$repetitions" -d "$outputDir" -f "$format" -t "$testData" > /dev/null
		java -jar benchmark-java/target/benchmark-java-1.0-jar-with-dependencies.jar -o csv -r "$repetitions" -d "$outputDir" -f "$format" -t "$testData" > /dev/null
		node ./benchmark-nodejs/init.js -o csv -r "$repetitions" -d "$outputDir" -f "$format" -t "$testData" > /dev/null
	else
		php ./benchmark-php/init.php b:r -o csv -r "$repetitions" -d "$outputDir" -t "$testData" > /dev/null
		java -jar benchmark-java/target/benchmark-java-1.0-jar-with-dependencies.jar -o csv -r "$repetitions" -d "$outputDir" -t "$testData" > /dev/null
		node ./benchmark-nodejs/init.js -o csv -r "$repetitions" -d "$outputDir" -t "$testData" > /dev/null
	fi
}

# ---------------------------------
# Create combined file & temp files
# ---------------------------------
function preprocessOutput () {
	
	phpCsv="${outDir}php-benchmark-output.csv"
	javaCsv="${outDir}java-benchmark-output.csv"
	nodeCsv="${outDir}nodejs-benchmark-output.csv"
	
	if [ -f "$outDir$phpCsv" ] && [ -r "$outDir$phpCsv" ] && [ -f "$outDir$javaCsv" ] && [ -r "$outDir$javaCsv" ] && [ -f "$outDir$nodeCsv" ] && [ -r "$outDir$nodeCsv" ]
	then
		# create combined file
		combined="${outputDir}combined-benchmark-output.csv"
		$(cat php-benchmark-output.csv <(tail -n+2 java-benchmark-output.csv) <(tail -n+2 nodejs-benchmark-output.csv) > $combined)
		
		# create temp files
		serializeTemp=$(mktemp /tmp/benchmark-serialize.csv.XXXXXX)
		$(awk -F\; '$2 != 0' $combined > $serializeTemp)
		
		deserializeTemp=$(mktemp /tmp/benchmark-deserialize.csv.XXXXXX)
		$(awk -F\; '$3 != 0' $combined > $deserializeTemp)
		
		sizeTemp=$(mktemp /tmp/benchmark-size.csv.XXXXXX)
		$(awk -F\; '$4 != 0' $combined > $sizeTemp)
	else
		error "Output files were not found or not redable."
	fi
}

# ------------------
# Delete temp files
# ------------------
function deleteTempFiles () {

	$(rm $serializeTemp)
	$(rm $deserializeTemp)
	$(rm $sizeTemp)
}

# ------------------
# Plot graphs
# ------------------
function plot () {
	
	# create combined file & temp files
	preprocessOutput
	
	# output image
	graphImage="${outputDir}benchmark-output.png"
	
	# plot
	gnuplot -persist <<-EOFMarker
		set terminal png font arial 12 size 1920,2800		# font size, size of image
		set output "$graphImage"								# name of output image
		set multiplot layout 3, 1							# 3 rows, 1 col
		
		set datafile separator ";"							# csv separator
		set style fill solid 0.8 border -1					# style of bar chart
		set boxwidth 0.5 relative							# style of bar chart
		set grid y											# show horizontal lines
		set logscale y										# logaritmics scale
		set xtics right rotate by 45						# rotate names of libs by 45 deg
		set key autotitle columnhead 						# skip first line in csv	
		unset key											# no title for data
		set lmargin 15										# margin so names could fit
		
		set ylabel "Time (ms)"								# label of y
		unset key
		set title "Serialize"								# title of graph
		unset key
		plot "$serializeTemp" using 2:xtic(1) with boxes, \
			 "" using 0:2:(sprintf("%.1f",\$2)) with labels center offset 0,0.5 notitle
		
		set ylabel "Time (ms)"
		unset key
		set title "Deserialize"
		unset key
		plot "$deserializeTemp" using 3:xtic(1) with boxes, \
			 "" using 0:3:(sprintf("%.1f",\$3)) with labels center offset 0,0.5 notitle
			
		set ylabel "Size (kB)"
		unset key
		set title "Size of serialized data"
		unset key
		plot "$sizeTemp" using 4:xtic(1) with boxes, \
			 "" using 0:4:(sprintf("%.1f",\$4)) with labels center offset 0,0.5 notitle
	EOFMarker
	
	# open image in the user's preferred app
	xdg-open $graphImage
	
	# just delete temp files
	deleteTempFiles
}


# let's do it
#runBenchmarks
plot

echo ""
echo "Benchmark run successfully!"
echo ""

exit 0
