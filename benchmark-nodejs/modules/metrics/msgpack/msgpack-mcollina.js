var Metric = require('../metric'); 
var lib = require('msgpack5')();


module.exports = new Metric(
		"msgpack",
		"mcollina/msgpack5", 
		"3.4.1", 
		"https://github.com/mcollina/msgpack5", 
		lib.encode, 
		lib.decode
	);
