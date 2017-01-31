var metric = require('../metric'); 


exports.metric = new metric.Metric(
		"json",
		"JSON", 
		null, 
		null, 
		JSON.stringify, 
		JSON.parse
	);
