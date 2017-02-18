#!/usr/bin/env bash

# ------------------
# Init variables
# ------------------
formatOptions=("native" "json" "xml" "protobuf" "msgpack" "avro")
format=
repetitions=100
outputDir="./"
testDataOptions=("small" "big")
testData=$(readlink -f "./testdata/test_data_${testDataOptions[0]}.json")
summarizeSerializeTemp=
summarizeDeserializeTemp=
sizeTemp=
combinedSerialize=
combinedDeserialize=



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
	echo ""
	echo "	Error: $1"
	echo ""
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
		-r | --repetitions )		shift
									setRepetitions $1
									;;
		-d | --out-dir )  			shift
									setOutputDir $1
		                        	;;
		-f | --format )  			shift  
									setFormat $1
		                        	;;
		-t | --data )  				shift
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

# ----------------------------------------------
# Create combined file & temp files for box plot
# ----------------------------------------------
function preprocessBarOutput () {
	
	phpCsv="${outDir}php-summarize.csv"
	javaCsv="${outDir}java-summarize.csv"
	nodeCsv="${outDir}nodejs-summarize.csv"
	
	if [ -r "$outDir$phpCsv" ] && [ -r "$outDir$javaCsv" ] && [ -r "$outDir$nodeCsv" ]
	then
		# create combined file
		combined="${outputDir}combined-summarize.csv"
		$(cat $phpCsv <(tail -n+2 $javaCsv) <(tail -n+2 $nodeCsv) > $combined)
		
		# create temp files
		summarizeSerializeTemp=$(mktemp /tmp/benchmark-serialize.csv.XXXXXX)
		$(awk -F\; '$2 != 0' $combined > $summarizeSerializeTemp)
		
		summarizeDeserializeTemp=$(mktemp /tmp/benchmark-deserialize.csv.XXXXXX)
		$(awk -F\; '$3 != 0' $combined > $summarizeDeserializeTemp)
		
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

	$(rm $summarizeSerializeTemp)
	$(rm $summarizeDeserializeTemp)
	$(rm $sizeTemp)
}

# ------------------
# Plot bar graphs
# ------------------
function plotBar () {
	
	# create combined file & temp files
	preprocessBarOutput
	
	# output image
	graphImage="${outputDir}benchmark-summarize.png"
	
	# plot
	gnuplot -persist <<-EOFMarker
		set terminal png font arial 12 size 1920,2800		# font size, size of image
		set output "$graphImage"							# name of output image
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
		
		set ylabel "Time logarithmic (ms)"					# label of y
		unset key
		set title "Serialize"								# title of graph
		unset key
		plot "$summarizeSerializeTemp" using 2:xtic(1) with boxes, \
			 "" using 0:2:(sprintf("%.1f",\$2)) with labels center offset 0,0.5 notitle
		
		set ylabel "Time logarithmic (ms)"
		unset key
		set title "Deserialize"
		unset key
		plot "$summarizeDeserializeTemp" using 3:xtic(1) with boxes, \
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



# ----------------------------------------------
# Create combined file & temp files for box plot
# ----------------------------------------------
function preprocessBoxOutput () {
	
	phpSerializeCsv="${outDir}php-serialize.csv"
	phpDeserializeCsv="${outDir}php-deserialize.csv"
	
	javaSerializeCsv="${outDir}java-serialize.csv"
	javaDeserializeCsv="${outDir}java-deserialize.csv"
	
	nodeSerializeCsv="${outDir}nodejs-serialize.csv"
	nodeDeserializeCsv="${outDir}nodejs-deserialize.csv"

	if [ -r "$outDir$phpSerializeCsv" ] && 
	[ -r "$outDir$phpDeserializeCsv" ] && 
	[ -r "$outDir$javaSerializeCsv" ] && 
	[ -r "$outDir$javaDeserializeCsv" ] && 
	[ -r "$outDir$nodeSerializeCsv" ] && 
	[ -r "$outDir$nodeDeserializeCsv" ]
	then
		# create combined files
		combinedSerialize="${outputDir}combined-serialize.csv"
		$(paste -d";" $phpSerializeCsv $javaSerializeCsv $nodeSerializeCsv > $combinedSerialize)
		
		combinedDeserialize="${outputDir}combined-deserialize.csv"
		$(paste -d";" $phpDeserializeCsv $javaDeserializeCsv $nodeDeserializeCsv > $combinedDeserialize)
	else
		error "Output files were not found or not redable."
	fi
}


# ------------------
# Plot bar graphs
# ------------------
function plotBox () {
	
	# create combined files
	preprocessBoxOutput
	
	# output image
	graphImage="${outputDir}benchmark-output.png"
	
	# plot
	gnuplot -persist <<-EOFMarker
		set terminal png font arial 12 size 1920,2000		# font size, size of image
		set output "$graphImage"							# name of output image
		set multiplot layout 2, 1							# 3 rows, 1 col
		
		set style fill solid 0.25 border -1
		set style boxplot outliers pointtype 7
		set style data boxplot
		set datafile separator ";"							# csv separator
		set xtics right rotate by 45						# rotate names of libs by 45 deg
		set grid y											# show horizontal lines
		set logscale y										# logaritmics scale
		set ylabel "Time logarithmic (ms)"					# label of y
		set lmargin 15										# margin so names could fit
		
		
		set title "Serialize"								# title of graph
		unset key
		column_number = system("awk -F';' '{print NF; exit}' $combinedSerialize ")
		set for [i=1:column_number] xtics (system("head -1 $combinedSerialize | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
		plot for [i=1:column_number] '$combinedSerialize' using (i):i notitle
		
		set title "Deserialize"								# title of graph
		unset key
		column_number = system("awk -F';' '{print NF; exit}' $combinedDeserialize ")
		set for [i=1:column_number] xtics (system("head -1 $combinedDeserialize | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
		plot for [i=1:column_number] '$combinedDeserialize' using (i):i notitle
	EOFMarker
	
	# open image in the user's preferred app
	xdg-open $graphImage
}




# let's do it
runBenchmarks
plotBar
plotBox

echo ""
echo "	Benchmark run successfully!"
echo ""

exit 0
