#!/usr/bin/env bash

# localisation settings (must be set for sort command)
export LC_ALL=C

# ------------------
# Init variables
# ------------------
formatOptions=("native" "json" "xml" "protobuf" "msgpack" "avro")
format=
languageOptions=("php" "java" "nodejs")
language=
outer="-o 30"
inner="-i 100"
outDir="./"
summarizeSerializeTemp=
summarizeDeserializeTemp=
sizeTemp=
combinedSerialize=
combinedDeserialize=



# ------------------
# Validation
# ------------------
function setOutDir () {
	if [ -d "$1" ] && [ -w "$1" ]
	then
		outDir=$1
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
			format="-f $1"
			return	
		fi
		formatString="$formatString $i"
	done
	error "Format must be one of these options: $formatString"
}

function setLanguage () {
	languageString=
	for i in ${languageOptions[@]}
	do
		if [ "$i" = "$1" ]
		then
			language=$1
			return	
		fi
		languageString="$languageString $i"
	done
	error "Language must be one of these options: $languageString"
}

function setOuter () {
	if [ $1 -gt 0 ]
	then
		outer="-o $1"
	else
		error "Number of outer repetitions must be greater then zero."
	fi
}

function setInner () {
	if [ $1 -gt 0 ]
	then
		inner="-i $1"
	else
		error "Number of inner repetitions must be greater then zero."
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
# Run benchmarks
# ------------------
function runBenchmarks () {

	# php
	docker run --rm -it -v "$PWD/benchmark-php:/opt/benchmark" -w /opt/benchmark darkling/benchmark-php:7.1 \
	sh -c " 
		composer install && \
		composer update && \
		php -d memory-limit=256 init.php b:r -r csv $outer $inner $format -d ./"

	$(mv ./benchmark-php/php-serialize.csv $outDir)
	$(mv ./benchmark-php/php-deserialize.csv $outDir)
	$(mv ./benchmark-php/php-summarize.csv $outDir)
	$(mv ./benchmark-php/php-info.txt $outDir)
	
	# nodeJS
	docker run --rm -it -v "$PWD/benchmark-nodejs:/opt/benchmark" -w /opt/benchmark node:7.6 \
	sh -c " 
		npm rebuild && \
		npm install && \
		node init.js -r csv $outer $inner $format -d ./"

	$(mv ./benchmark-nodejs/nodejs-serialize.csv $outDir)
	$(mv ./benchmark-nodejs/nodejs-deserialize.csv $outDir)
	$(mv ./benchmark-nodejs/nodejs-summarize.csv $outDir)
	$(mv ./benchmark-nodejs/nodejs-info.txt $outDir)
	
	# java
	docker run --rm -it -v "$PWD/benchmark-java:/opt/benchmark" -w /opt/benchmark darkling/benchmark-java:8 \
	sh -c " 
		mvn package && \
		java -jar target/benchmark-java-1.0-jar-with-dependencies.jar -r csv $outer $inner $format -d ./"

	$(mv ./benchmark-java/java-serialize.csv $outDir)
	$(mv ./benchmark-java/java-deserialize.csv $outDir)
	$(mv ./benchmark-java/java-summarize.csv $outDir)
	$(mv ./benchmark-java/java-info.txt $outDir)
}


# ----------------------------------------------
# Create combined file & temp files for box plot
# ----------------------------------------------
function preprocessBarOutput () {
	
	phpCsv="${outDir}php-summarize.csv"
	javaCsv="${outDir}java-summarize.csv"
	nodeCsv="${outDir}nodejs-summarize.csv"
	
	if [ -r "$phpCsv" ] && 
	[ -r "$javaCsv" ] && 
	[ -r "$nodeCsv" ]
	then
		# create combined file
		combined="${outDir}combined-summarize.csv"
		$(cat $phpCsv <(tail -n+2 $javaCsv) <(tail -n+2 $nodeCsv) > $combined)
	
		# create temp files
		summarizeSerializeTemp=$(mktemp /tmp/benchmark-serialize.csv.XXXXXX)
		$(tail -n+2 $combined | awk -F";" '$2 != 0 {gsub("\"","", $2); print $1";"$2}' | sort -t";" -nk2 > $summarizeSerializeTemp)
		
		summarizeDeserializeTemp=$(mktemp /tmp/benchmark-deserialize.csv.XXXXXX)
		$(tail -n+2 $combined | awk -F";" '$3 != 0 {gsub("\"","", $3); print $1";"$3}' | sort -t";" -nk2 > $summarizeDeserializeTemp)
		
		sizeTemp=$(mktemp /tmp/benchmark-size.csv.XXXXXX)
		$(tail -n+2 $combined | awk -F";" '$4 != 0 {gsub("\"","", $4); print $1";"$4}' | sort -t";" -nk2 > $sizeTemp)
	else
		error "Output files were not found or not readable."
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
	graphImage="${outDir}benchmark-bar.png"
	
	# plot
	gnuplot -persist <<-EOFMarker
		set terminal png font arial 12 size 1920,2800
		set output "$graphImage"
		set multiplot layout 3, 1
		set lmargin 25
		set datafile separator ";"
		set grid y
		set logscale y
		set xtics right rotate by 45
		set style fill solid border -1
		
		bgcolor(value) = (language=word(value, 1), language eq "php" ? 1 : language eq "java" ? 2 : 3)
		
		set ylabel "Time logarithmic (ms)"
		unset key
		set title "Serialize"
		unset key
		# using #xval:ydata:boxwidth:color_index:xtic_labels
		plot "$summarizeSerializeTemp" using (column(0)):2:(0.5):(bgcolor(stringcolumn(1))):xtic(1) with boxes lc variable, \
			 "" using 0:2:(sprintf("%.1f",\$2)) with labels center offset 0,0.5 notitle
		
		set ylabel "Time logarithmic (ms)"
		unset key
		set title "Deserialize"
		unset key
		plot "$summarizeDeserializeTemp" using (column(0)):2:(0.5):(bgcolor(stringcolumn(1))):xtic(1) with boxes lc variable, \
			 "" using 0:2:(sprintf("%.1f",\$2)) with labels center offset 0,0.5 notitle
			
		set ylabel "Size (kB)"
		unset key
		set title "Size of serialized data"
		unset key
		plot "$sizeTemp" using (column(0)):2:(0.5):(bgcolor(stringcolumn(1))):xtic(1) with boxes lc variable, \
			 "" using 0:2:(sprintf("%.1f",\$2)) with labels center offset 0,0.5 notitle
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

	if [ -r "$phpSerializeCsv" ] && 
	[ -r "$phpDeserializeCsv" ] && 
	[ -r "$javaSerializeCsv" ] && 
	[ -r "$javaDeserializeCsv" ] && 
	[ -r "$nodeSerializeCsv" ] && 
	[ -r "$nodeDeserializeCsv" ]
	then
		# create combined files
		combinedSerialize="${outDir}combined-serialize.csv"
		$(paste -d";" $phpSerializeCsv $javaSerializeCsv $nodeSerializeCsv > $combinedSerialize)
		
		combinedDeserialize="${outDir}combined-deserialize.csv"
		$(paste -d";" $phpDeserializeCsv $javaDeserializeCsv $nodeDeserializeCsv > $combinedDeserialize)
	else
		error "Output files were not found or not readable."
	fi
}


# ------------------
# Plot bar graphs
# ------------------
function plotBox () {
	
	# create combined files
	preprocessBoxOutput
	
	# output image
	graphImage="${outDir}benchmark-box.png"
	
	# plot
	gnuplot -persist <<-EOFMarker
		set terminal png font arial 12 size 1920,2000
		set output "$graphImage"
		set multiplot layout 2, 1
		
		set style fill solid 0.25 border -1
		set style boxplot outliers pointtype 7
		set style data boxplot
		set datafile separator ";"
		set xtics right rotate by 45
		set grid y
		set logscale y
		set ylabel "Time logarithmic (ms)"
		set lmargin 25
		
		set title "Serialize"
		unset key
		column_number = system("awk -F';' '{print NF; exit}' $combinedSerialize ")
		set for [i=1:column_number] xtics (system("head -1 $combinedSerialize | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
		plot for [i=1:column_number] '$combinedSerialize' using (i):i notitle
		
		set title "Deserialize"
		unset key
		column_number = system("awk -F';' '{print NF; exit}' $combinedDeserialize ")
		set for [i=1:column_number] xtics (system("head -1 $combinedDeserialize | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
		plot for [i=1:column_number] '$combinedDeserialize' using (i):i notitle
	EOFMarker
	
	# open image in the user's preferred app
	xdg-open $graphImage
}


# ------------------
# Read options
# ------------------
while [ "$1" != "" ]; do
	case $1 in
		-o | --outer )		
			shift
			setOuter $1
			;;
		-i | --inner )		
			shift
			setInner $1
			;;
		-d | --out-dir )  			
			shift
			setOutDir $1
		    ;;
		-f | --format )  			
			shift  
			setFormat $1
			;;
		-l | --language )  			
			shift  
			setLanguage $1
			;;
		-h | --help )           	
			printHelp	
			;;
		* )                     	
			error "Unknown argument $1"
    	esac
	shift
done

# let's do it
runBenchmarks
plotBar
plotBox

echo ""
echo "Benchmark run successfully!"
echo ""

exit 0
