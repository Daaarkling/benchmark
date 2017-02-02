var Metric = require('../metric'); 
var lib = require('data2xml')({});

// add class definition
var Data2xmlClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
Data2xmlClass.prototype = new Metric();

// override method serializeImpl()
Data2xmlClass.prototype.serializeImpl = function (testData) {
	return lib("data", testData)
}

// create new object
module.exports = new Data2xmlClass(
		"xml",
		"chilts/data2xml", 
		"1.2.5", 
		"https://github.com/chilts/data2xml", 
		lib, 
		null
	);
