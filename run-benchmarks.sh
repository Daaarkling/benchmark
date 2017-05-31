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
outer="-o 50"
inner="-i 100"
chatty=
outDir="./"
summarizeSerializeTemp=
summarizeDeserializeTemp=
sizeTemp=
combinedSerialize=
combinedDeserialize=
divisor=3
limit=12


# ------------------
# Validation
# ------------------
function setFormat () {
	formatString=
	for i in ${formatOptions[@]}
	do
		if [[ $i = $1 ]]
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
		if [[ $i = $1 ]]
		then
			language=$1
			return	
		fi
		languageString="$languageString $i"
	done
	error "Language must be one of these options: $languageString"
}

function setOuter () {
	if (( $1 > 0 ))
	then
		outer="-o $1"
	else
		error "Number of outer repetitions must be greater then zero."
	fi
}

function setInner () {
	if (( $1 > 0 ))
	then
		inner="-i $1"
	else
		error "Number of inner repetitions must be greater then zero."
	fi
}

function setChatty () {
	chatty="-c"
}

# ------------------
# Print
# ------------------
function error () {
	printf "\n\tError: $1\n\n";
	exit 1
}

function printHelp () {
	echo "TODO"
	exit 0
}


# -----------------------
# Create output directory
# -----------------------
function createOutDir () {
	outDir="output-"$(date +%Y%m%d-%H%M%S)
	mkdir $outDir
	outDir="${PWD}/${outDir}/"
}

# ------------------
# Run benchmarks
# ------------------
function runBenchmarks () {
	executed=false

	# php
	if [[ ("$language" == "php" || "$language" == "") && "$(docker images -q darkling/benchmark-php:7.1 2> /dev/null)" != "" ]]
	then
		docker run --rm -it -v "$outDir:/opt/output" darkling/benchmark-php:7.1 \
		sh -c " 
			php init.php b:r -r csv $outer $inner $format $chatty -d /opt/output"
			
		executed=true
	fi
	
	# nodeJS
	if [[ ("$language" == "nodejs" || "$language" == "") && "$(docker images -q darkling/benchmark-nodejs:7.7 2> /dev/null)" != "" ]]
	then
		docker run --rm -it -v "$outDir:/opt/output" darkling/benchmark-nodejs:7.7 \
		sh -c " 
			node init.js -r csv $outer $inner $format $chatty -d /opt/output"
		
		executed=true
	fi
	
	# java
	if [[ ("$language" == "java" || "$language" == "") && "$(docker images -q darkling/benchmark-java:8 2> /dev/null)" != "" ]]
	then
		docker run --rm -it -v "$outDir:/opt/output" darkling/benchmark-java:8 \
		sh -c " 
			java -jar target/benchmark-java-1.0-jar-with-dependencies.jar -r csv $outer $inner $format $chatty -d /opt/output"
		
		executed=true
	fi
	
	
	if [[ $executed == false ]]
	then
		error "Run 'build-docker.sh' script first."
	fi
}


# ----------------------------------------------
# Create combined file & temp files for box plot
# ----------------------------------------------
function preprocessBarOutput () {
	# create combined file
	combined="${outDir}combined-summarize.csv"

	# find all files with name *-summarize.csv except combined-summarize.csv
	files=$(find $outDir -name "*-summarize.csv" -and -not -name "combined-summarize.csv")
	i=1
	for f in $files
	do
		if (( $i == 1 ))
		then
			# just for once include header line
			$(head -1 $f > $combined)
		fi

		# add content of file into combined file except first line (header)
		$(tail -n+2 $f >> $combined)

		# add newline
		$(printf "\n" >> $combined)

		((i++))
	done

	# remove all empty lines
	#$(sed '/^\s*$/d' $combined > ${combined}.tmp; mv ${combined}.tmp $combined)
	$(awk 'NF > 0' $combined > ${combined}.tmp; mv ${combined}.tmp $combined)


	# Create temp files for each category
	# for serialize:
	# - skip first row
	# - include row only if second column is not zero (missing value)
	# - remove double quotes around numeric value
	# - sort file by second values
	summarizeSerializeTemp=$(mktemp /tmp/benchmark-serialize.csv.XXXXXX)
	$(awk -F";" 'NR >= 2 && $2 != 0 {gsub("\"","", $2); print $1";"$2}' $combined | sort -t";" -nk2 > $summarizeSerializeTemp)

	summarizeDeserializeTemp=$(mktemp /tmp/benchmark-deserialize.csv.XXXXXX)
	$(awk -F";" 'NR >= 2 && $3 != 0 {gsub("\"","", $3); print $1";"$3}' $combined | sort -t";" -nk2 > $summarizeDeserializeTemp)

	sizeTemp=$(mktemp /tmp/benchmark-size.csv.XXXXXX)
	$(awk -F";" 'NR >= 2 && $4 != 0 {gsub("\"","", $4); print $1";"$4}' $combined | sort -t";" -nk2 > $sizeTemp)
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
		
		#bgcolor(value) = (language=word(value, 1), language eq "php" ? "119,123,179" : language eq "java" ? "234,45,47" : "144,197,63")
		rgb(r,g,b) = 65536 * int(r) + 256 * int(g) + int(b)
		bgcolor(value) = (language=word(value, 1), language eq "php" ? rgb(119,123,179) : language eq "java" ? rgb(234,45,47) : 2)
		
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
}



# ---------------------------------------------------
# Preprocess csv files for box plot
#
# Function preprocessBarOutput must be called first
# @return echo names of files (separator is newline)
# ---------------------------------------------------
function preprocessBoxOutput () {
	serializeResult=$(createBoxOutputFiles "serialize" $summarizeSerializeTemp)
	deserializeResult=$(createBoxOutputFiles "deserialize" $summarizeDeserializeTemp)

	# join results together (-e option interprets \n as newline)
	echo -e "${serializeResult}\n${deserializeResult}"
}


# -----------------------------------------------------------
# Create combined file & fragment files for box plot
#
# @param string serialize|deserialize
# @param file summarizeSerializeTemp|summarizeDeserializeTemp
# @return echo names of created files (separator is newline)
# -----------------------------------------------------------
function createBoxOutputFiles () {
	# Combined file
	#--------------

	# prepare combined file
	combined="${outDir}combined-${1}.csv"

	# find all files with name *-(serialize|deserialize).csv except combined-(serialize|deserialize).csv
	files=$(find $outDir -name "*-${1}.csv" -and -not -name "combined-${1}.csv")

	# add content (side-by-side) of those files into one big file
	$(paste -d";" $files > $combined)

	# store result
	result="${combined}\n"


	# Fragment files
	#---------------

	summarizeTemp=$2

	# count number of libs
	count=$(wc -l < $summarizeTemp)

	# if there is small number of libs, no need to do that
	if (( $count >= limit ))
	then
		step=$(( count / divisor ))
		cuts=()
		start=-step
		end=0
		for (( i=0; i<$divisor; i++ ))
		do
			# over iteration shift start and end so it contains all libs
			start=$(( start + step))
			# in the last iteration include all the rest of libs
			(( (i+1) == divisor )) && end=10000 || end=$(( end + step))

			# divide libs into divisor of number variables and store them into array named cuts
			cuts[i]=$(awk -F';' -v start="$start" -v end="$end" 'NR > start && NR <= end {print $1}' $summarizeTemp)
		done

		# call transpose function, awk is going through file line by line so we have to do it
		combinedSerializeTranspose=$(transpose $combined)

		# we iterate over all cuts
		i=1
		for cut in "${cuts[@]}"
		do
			# prepare output file and temp file
			outFile=${outDir}combined-${1}-${i}.csv
			tmpOutFile=${outFile}.tmp

			# iterate over all libs names in given cut (separated by newline "read -r")
			echo "$cut" | while read -r name
			do
				# select libs in transpose combined file but only those which match by name and add them into temp file
				$(echo "$combinedSerializeTranspose" | awk -F';' -v name="$name" '$1 == name { print $0;exit }' >> $tmpOutFile)
			done

			# transpose temp file back
			transposeBack=$(transpose $tmpOutFile)

			# store transposed data into final file
			$(echo "$transposeBack" > $outFile)

			# delete temp file
			$(rm $tmpOutFile)

			# add file into result
			result+="${outFile}\n"

			((i++))
		done
	fi

	# remove all empty lines (-e option interprets \n as newline)
	result=$(echo -e "$result" | awk 'NF > 0')

	#return
	echo "$result"
}


# ----------------------------
# Transpose csv file (;)
#
# @param file
# @return echo transposed data
# ----------------------------
function transpose () {
	transposed=$(awk -F';' '
	{
		for (i=1; i<=NF; i++)  {
			a[NR,i] = $i
		}
	}
	NF>p { p = NF }
	END {
		for(j=1; j<=p; j++) {
			str=a[1,j]
			for(i=2; i<=NR; i++){
				str=str";"a[i,j];
			}
			print str
		}
	}' $1)

	echo "$transposed"
}



# ------------------
# Plot box graphs
# ------------------
function plotBox () {
	# create combined files, convert result into array
	files=($(preprocessBoxOutput))

	length=${#files[@]}

	# iterate over half of files (first half are serialization libs, second are deserialization libs)
	for (( i=0; i<$length/2; i++ ))
	do
		# choose deserialization lib equivalent to serialization lib, so skip by number of fragments plus one combined file
		j=$(( i+divisor+1 ))

		serializationFile=${files[$i]}
		deserializationFile=${files[$j]}

		# output image
		graphImage="${outDir}benchmark-box-${i}.png"

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
			set ylabel "Time (ms)"
			set lmargin 25

			set title "Serialize"
			unset key
			column_number = system("awk -F';' '{print NF; exit}' $serializationFile ")
			set for [i=1:column_number] xtics (system("head -1 $serializationFile | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
			plot for [i=1:column_number] '$serializationFile' using (i):i notitle

			set title "Deserialize"
			unset key
			column_number = system("awk -F';' '{print NF; exit}' $deserializationFile ")
			set for [i=1:column_number] xtics (system("head -1 $deserializationFile | sed -e 's/\"//g' | awk -F';' '{print $" . i . "}'") i)
			plot for [i=1:column_number] '$deserializationFile' using (i):i notitle
		EOFMarker

		# open image in the user's preferred app
		xdg-open $graphImage
	done
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
# Let's do it
# ------------------
function init () {
	createOutDir
	runBenchmarks

	if [[ "$(which gnuplot)" != "" ]]
	then
		plotBar
		plotBox

		# just delete temp files
		deleteTempFiles
	else
		echo "Gnuplot was not found. Please install it."
	fi

	printf "\n\tBenchmark run successfully!\n\n"
	exit 0
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
		-f | --format )
			shift
			setFormat $1
			;;
		-c | --chatty )
			shift
			setChatty
			;;
		-l | --language )
			shift
			setLanguage $1
			;;
		-h | --help )
			printHelp
			;;
		* )
			error "Unknown option $1"
		esac
	shift
done

# GO
init