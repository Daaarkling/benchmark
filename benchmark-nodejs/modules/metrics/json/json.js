var Metric = require('../metric'); 


module.exports = new Metric(
		"json",
		"stringify(), parse()", 
		null, 
		null, 
		JSON.stringify, 
		JSON.parse
	);
