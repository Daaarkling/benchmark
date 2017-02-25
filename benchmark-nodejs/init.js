var	benchmark = require("./modules/benchmark");
var path = require("path");
var	cli = require("commander");
var fs = require("fs");

var	formats = ["json", "xml", "msgpack", "protobuf", "avro"];
var	outputs = ["console", "csv"];


// configurate command options
cli.option("-r, --result <s>", "Handle output, you can choose from several choices: " + outputs.join(", "))
	.option("-d --out-dir <s>", "Output directory, only for 'csv' and 'file' output options.")
	.option("-t --test-data <s>", "Path to test data.")
	.option("-i, --inner <n>", "Number of inner repetitions.")
	.option("-o, --outer <n>", "Number of outer repetitions.")
  	.option("-f, --format <s>", "Run benchmark for specific format only.")
  	.parse(process.argv);


// create config with default values
var config = {
	testData: null,
	testDataSize: 0,
	inner: 100,
	outer: 30,
	result: "console",
	outDir: "./",
	format: null,
	version: process.version
};


// set config values from cli
if(cli.testData) {
	testData(cli.testData);
} else {
	testData("./testdata/test_data_small.json");
}

function testData(filePath) {
	var stats = fs.lstatSync(filePath);
	if (stats.isFile()) {
		try {
			fs.accessSync(filePath, fs.constants.R_OK);
			config.testData = require(path.resolve(filePath));
			config.size = (stats.size / 1024).toFixed(2) + " (kB)";
		} catch (err) {
			console.error("\n  error: Test data file is not redeable.\n");
			process.exit(1);
		}
	} else {
		console.error("\n  error: Test data must be file.\n");
		process.exit(1);
	}
} 

if(cli.outer) {
	if (parseInt(cli.outer) && cli.outer > 0) {
		config.outer = parseInt(cli.outer);
	} else {
		console.error("\n  error: Number of outer repetitions must be whole number greater than zero.\n");
		process.exit(1);
	}
}

if(cli.inner) {
	if (parseInt(cli.inner) && cli.inner > 0) {
		config.inner = parseInt(cli.inner);
	} else {
		console.error("\n  error: Number of inner repetitions must be whole number greater than zero.\n");
		process.exit(1);
	}
}

if(cli.format) {
	if (formats.includes(cli.format)) {
		config.format = cli.format;
	} else {
		console.error("\n  error: Format must be one of these options: " + formats.join(", ") + "\n");
		process.exit(1);
	}
}

if(cli.result) {
	if (outputs.includes(cli.result)) {
		config.result = cli.result;
	} else {
		console.error("\n  error: Result must be one of these options: " + outputs.join(", ") + "\n");
		process.exit(1);
	}
}

if(cli.result === "csv" && cli.outDir) {
	if (fs.lstatSync(cli.outDir).isDirectory()) {
		try {
			fs.accessSync(cli.outDir, fs.constants.W_OK);
			config.outDir = cli.outDir;
		} catch (err) {
			console.error("\n  error: Output directory is not writable.\n");
			process.exit(1);
		}
	} else {
		console.error("\n  error: Output path must be directory.\n");
		process.exit(1);
	}
}



// run
benchmark.run(config);

console.log("\n Benchmark processed successfully!\n ");

