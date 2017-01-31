var glob = require("glob"),
  	path = require("path");


exports.run = function(config) {
	
	// find all libs
	var metrics = [];
	glob.sync("./modules/metrics/**/!(*metric).js").forEach(function(file) {
		metrics.push(require(path.resolve(file)));
	});
	

	// filter by type if given
	if(config.format) {
		metrics = metrics.filter(function(item) {
			return item.metric.format === config.format;
		});
	}
	

	// run benchmarks
	result = [];
	metrics.forEach(function(item) {
		result.push(item.metric.run(config.testdata, config.repetitions));
	});
	
	
	console.log(result);
	
}


