var Metric = require('../metric'); 
var protobuf = require("protocol-buffers");
var fs = require("fs");

// pass a proto file as a buffer/string or pass a parsed protobuf-schema object
//
// doesnt handle import in proto files
// doesnt properly handle enum
var messages = protobuf(fs.readFileSync(__dirname + '/all.proto'))


module.exports = new Metric(
		"protobuf",
		"mafintosh/protocol-buffers", 
		"3.2.1", 
		"https://github.com/mafintosh/protocol-buffers", 
		messages.PersonCollection.encode, 
		messages.PersonCollection.decode
	);
