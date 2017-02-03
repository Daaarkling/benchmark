var Metric = require("../metric"); 
var PersonCollectionPb = require("../../converters/protobuf/person_collection_pb");
var converter = require("../../converters/protobuf/google-converter")


// add class definition
var GoogleProtobufClass = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

// declare it is a subclass of the Metric
GoogleProtobufClass.prototype = new Metric();

// override method
GoogleProtobufClass.prototype.prepareDataForSerialize = function (testData) {
	
	return converter.convert(testData);
}

// override method
GoogleProtobufClass.prototype.serializeImpl = function (message) {
	
	return message.serializeBinary();
}

// override method
GoogleProtobufClass.prototype.deserializeImpl = function (bytes) {
	
	return proto.PersonCollection.deserializeBinary(bytes).toObject();
}

// create new object
module.exports = new GoogleProtobufClass(
		"protobuf",
		"google/protobuf", 
		"3.1.0", 
		"https://github.com/google/protobuf/tree/master/js", 
		true, 
		true
	);
