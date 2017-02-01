var	benchmark = require("./modules/benchmark");
var path = require("path");
var	cli = require("commander");
var fs = require('fs');

var	formats = ["json", "xml", "msgpack"];
var	outputs = ["console", "csv", "file"];


// configurate command options
cli.option("-o, --output <s>", "Handle output, you can choose from several choices: " + outputs.join(", "))
	.option("-d --out-dir <s>", "Output directory, only for 'csv' and 'file' output options.")
	.option("-t --test-data <s>", "Path to test data.")
	.option("-r, --repetitions <n>", "Number of repetitions.")
  	.option("-f, --format <s>", "Run benchmark for specific format only.")
  	.parse(process.argv);


// create config with default values
var config = {
	testData: null,
	repetitions: 1000,
	output: "console",
	outDir: "./",
	format: ""
};

// set config values from cli
if(cli.testData) {
	if (fs.lstatSync(cli.testData).isFile()) {
		try {
			fs.accessSync(cli.testData, fs.constants.R_OK);
			config.testData = require(path.resolve(cli.testData));
		} catch (err) {
			console.error("\n  error: Test data file is nor redeable.\n");
			process.exit(1);
		}
	} else {
		console.error("\n  error: Test data must be file.\n");
		process.exit(1);
	}
} else {
	config.testData = require("./testdata/test_data_small.json");
}

// set config values from cli
if(cli.output) {
	if (formats.includes(cli.format)) {
		config.format = cli.format;
	} else {
		console.error("\n  error: Format must be one of these options: " + formats.join(", ") + "\n");
		process.exit(1);
	}
}

if(cli.repetitions) {
	if (parseInt(cli.repetitions) && cli.repetitions > 0) {
		config.repetitions = parseInt(cli.repetitions);
	} else {
		console.error("\n  error: Repetitions must be whole number greater than zero.\n");
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

if(cli.output) {
	if (outputs.includes(cli.output)) {
		config.output = cli.output;
	} else {
		console.error("\n  error: Output must be one of these options: " + outputs.join(", ") + "\n");
		process.exit(1);
	}
}

if((cli.output === "csv" || cli.output === "file") && cli.outDir) {
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

