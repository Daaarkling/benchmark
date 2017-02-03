var Metric = require('../metric'); 
var protobuf = require("protobufjs");

var root = protobuf.loadSync(__dirname + "/person_collection.proto");
var PersonCollection = root.lookup("PersonCollection");

// add class definition
var ProtobufjsClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
ProtobufjsClass.prototype = new Metric();

ProtobufjsClass.prototype.prepareDataForSerialize = function (testData) {
	return PersonCollection.create(testData);
};

ProtobufjsClass.prototype.serializeImpl = function (message) {
	return PersonCollection.encode(message).finish();
};

ProtobufjsClass.prototype.deserializeImpl = function (buffer) {
	return PersonCollection.decode(buffer)
};

// create new object
module.exports = new ProtobufjsClass(
		"protobuf",
		"dcodeIO/protobuf.js", 
		"6.6.3", 
		"https://github.com/dcodeIO/protobuf.js", 
		true, 
		true
	);
