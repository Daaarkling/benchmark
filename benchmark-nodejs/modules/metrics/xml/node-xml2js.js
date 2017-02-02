var Metric = require('../metric'); 
var lib = require('xml2js');
var converter = require('../../converters/xml-converter');

// add class definition
var NodeXml2jsClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
NodeXml2jsClass.prototype = new Metric();

//override method
NodeXml2jsClass.prototype.prepareDataForDeserialize = function (testData) {
	
	return converter.convert(testData);	
};

// override method
NodeXml2jsClass.prototype.deserializeImpl = function (serializedData) {
	
	lib.parseString(serializedData, function (err, result) {
		return result;
	});
}

// create new object
module.exports = new NodeXml2jsClass(
		"xml",
		"Leonidas-from-XIV/node-xml2js", 
		"0.4.17", 
		"https://github.com/Leonidas-from-XIV/node-xml2js", 
		null, 
		true
	);
