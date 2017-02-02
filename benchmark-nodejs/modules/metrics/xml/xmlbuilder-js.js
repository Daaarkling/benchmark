var Metric = require('../metric'); 
var lib = require('xmlbuilder');

// add class definition
var XmlbuilderJsClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
XmlbuilderJsClass.prototype = new Metric();

// override method serializeImpl()
XmlbuilderJsClass.prototype.serializeImpl = function (testData) {
	return lib.create("data").ele(testData).end({pretty: false}).toString();
}

// create new object
module.exports = new XmlbuilderJsClass(
		"xml",
		"oozcitak/xmlbuilder-js", 
		"8.2.2", 
		"https://github.com/oozcitak/xmlbuilder-js", 
		lib.create, 
		null
	);
