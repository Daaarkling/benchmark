var glob = require("glob");
var path = require("path");
var outputs = require("./outputs");


exports.run = function(config) {
	
	// find all libs
	var metrics = [];
	glob.sync(__dirname + "/metrics/**/!(*metric).js").forEach((file) => {
		metrics.push(require(path.resolve(file)));
	});


	// filter by type if given
	if(config.format) {
		metrics = metrics.filter((metric) => {
			return metric.format === config.format;
		});
	}

	
	// run benchmarks
	result = [];
	metrics.forEach((metric) => {
		result.push(metric.run(config.testData, config.repetitions));
	});
	
	var info = {
		version: process.version,
		size: (config.testDataSize / 1024).toFixed(2) + " (kB)",
		outer: global.OUTER_REPETITION,
		inner: config.repetitions,
		date: new Date().toISOString()
	};
	
	// hande output (run function by its string name)
	outputs[config.output](result, info, config.outDir);
}
