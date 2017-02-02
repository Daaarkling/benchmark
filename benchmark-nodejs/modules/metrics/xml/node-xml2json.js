var Metric = require('../metric'); 
var lib = require('xml2json');
var converter = require('../../converters/xml-converter');


// add class definition
var Xml2jsonClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
Xml2jsonClass.prototype = new Metric();

// override method
Xml2jsonClass.prototype.prepareDataForSerialize = function (testData) {
	
	var xml = converter.convert(testData);
	return lib.toJson(xml, {object: true, reversible: true});
}

// override method
Xml2jsonClass.prototype.deserializeImpl = function (serializedData) {
	
	return lib.toJson(serializedData, {object: true, reversible: true});	
};

// create new object
module.exports = new Xml2jsonClass(
		"xml",
		"buglabs/node-xml2json", 
		"0.11.0", 
		"https://github.com/buglabs/node-xml2json", 
		lib.toXml, 
		lib.toJson
	);
