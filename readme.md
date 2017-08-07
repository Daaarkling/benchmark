# Benchmark of (de)serialization formats in PHP, JAVA and NodeJS
Comparison of data (de)serialization formats in PHP, JAVA, NODEJS

Requirements
----------
Benchmark is designed for Linux system. To avoid the need to install PHP, JAVA and NodeJS in proper version and with all dependencies, docker images are ready to use. Therefore only requirement is Docker. To install it on your system follow this link [https://www.docker.com/](https://www.docker.com/)

Optional requirement is [GNUPLOT v2 tool](http://www.gnuplot.info/) for plotting graphs.
For Debian or Ubuntu simple run ```sudo apt-get install gnuplot2```

Usage
----------
1. Clone or download this repository
2. Go into directory ```cd benchmark```
3. Add executable permission ```sudo chmod +x build-docker.sh run-benchmarks.sh```
4. Build docker images ```./build-docker.sh``` (can be executed with language option)
5. Run benchmark ```./run-benchmarks.sh``` (benchmark can be run with many options)

Options
----------
* ```-i, --inner <n>``` number of inner repetitions
* ```-o, --outer <n>``` number of outer repetitions
* ```-f, --format <s>``` run benchmark only for one given format (xml, json, avro, msgpack, protobuf, native)
* ```-l, --language <s>``` run benchmark only for one given language (php, java, nodejs)
* ```-c, --chatty``` enable verbose (chatty) mode

For example: ```./run-benchmarks.sh -i 100 -o 10 -f json -l php -c```

To understand inner and outer repetitions just check out this java code and all should be clear.
```java
for (int j = 0; j < OUTER; j++){
    start = System.nanoTime();
    for (int i = 0; i < INNER; i++) {
        // call (de)serialize method
        //...
    }
    time = System.nanoTime() - start;
    //...
}
```

Output
----------
After the benchmark is done. Output directory with name of current date is created. This dir contains multiple files such as:
* CSV file with times of serialization
* CSV file with times of deserialization
* CSV file with computed means of times of serialization and deserialization plus size of serialized data
* TXT file with info about benchmark

All these files are created for each language. These files are then combined into single CSV files from which image files are created. Into these images Bar and Box graph are plotted. 

Times of serialization and deserialization are in milliseconds (ms) units and sizes of serialized data are in kilobytes (kB).