var glob = require("glob");
var path = require("path");
var outputs = require("./outputs");


exports.run = function(config, logger) {
	
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
		if (logger) {
			logger.startMessage(metric.fullName());
		}
	
		result.push(metric.run(config.testData, config.inner, config.outer));
		
		if (logger) {
			logger.endMessage(metric.fullName());
		}
	});

	
	// hande output (run function by its string name)
	outputs[config.result](result, config);
}
