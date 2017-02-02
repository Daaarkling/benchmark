var Metric = require('../metric'); 
var lib = require('jstoxml');


module.exports = new Metric(
		"xml",
		"davidcalhoun/jstoxml", 
		"0.2.4", 
		"https://github.com/davidcalhoun/jstoxml", 
		lib.toXML, 
		null
	);
