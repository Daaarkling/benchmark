var testdata = require("./testdata/test_data_small.json"),
	benchmark = require("./modules/benchmark"),
	validators = require("./modules/validators")
	program = require("commander");
	formats = ["json", "xml", "msgpack"];


// configurate command options
program
	.option("-r, --repetitions <n>", "Number of repetitions.")
  	.option("-f, --format <s>", "Run benchmark for specific format only.")
  	.parse(process.argv);


// create config with default values
var config = {
	testdata: testdata,
	repetitions: 1000,
	format: ""
};


// set config values
if(program.repetitions) {
	if (validators.repetitions(program.repetitions)) {
		config.repetitions = parseInt(program.repetitions);
	} else {
		console.error("Repetitions must be whole number greater than zero.");
		process.exit(1);
	}
}

if(program.format) {
	if (validators.format(program.format, formats)) {
		config.format = program.format;
	} else {
		console.error("Format must be one of these options: " + formats.join(", "));
		process.exit(1);
	}
}

//console.log(config);

// run
benchmark.run(config);



console.log("Done!");
