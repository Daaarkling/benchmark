var Metric = require('../metric'); 
var lib = require('js2xmlparser');

// add class definition
var Js2xmlparserClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
Js2xmlparserClass.prototype = new Metric();

// override method serializeImpl()
Js2xmlparserClass.prototype.serializeImpl = function (testData) {
	return lib.parse("data", testData);
}

// create new object
module.exports = new Js2xmlparserClass(
		"xml",
		"michaelkourlas/node-js2xmlparser", 
		"2.0.2", 
		"https://github.com/michaelkourlas/node-js2xmlparser", 
		lib.parse, 
		null
	);
