var Metric = require('../metric'); 
var EasyXml = require('easyxml');

// add class definition
var EasyXmlClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
EasyXmlClass.prototype = new Metric();

// override method serializeImpl()
EasyXmlClass.prototype.serializeImpl = function (testData) {
	
	return new EasyXml({
		singularize: true,
		rootElement: 'data',
		manifest: true
	}).render(testData);
}

// create new object
module.exports = new EasyXmlClass(
		"xml",
		"tlhunter/node-easyxml", 
		"2.0.1", 
		"https://github.com/tlhunter/node-easyxml", 
		true, 
		null
	);
