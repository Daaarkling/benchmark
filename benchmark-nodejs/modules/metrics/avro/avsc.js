var Metric = require("../metric"); 
var avro = require("avsc");

var type = avro.parse(require(__dirname + "/avro_schema.json"));

// add class definition
var AvscClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
AvscClass.prototype = new Metric();

AvscClass.prototype.serializeImpl = function (testData) {
	return type.toBuffer(testData);
};

AvscClass.prototype.deserializeImpl = function (data) {
	return type.fromBuffer(data)
};

module.exports = new AvscClass(
		"avro",
		"mtth/avsc", 
		"4.1.11", 
		"https://github.com/mtth/avsc", 
		true, 
		true
	);
