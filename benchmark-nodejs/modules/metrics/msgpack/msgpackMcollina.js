var benchmark = require('../metric'); 
var lib = require('msgpack5')();


exports.metric = new benchmark.Metric(
		"msgpack",
		"mcollina/msgpack5", 
		"3.4.1", 
		"https://github.com/mcollina/msgpack5", 
		lib.encode, 
		lib.decode
	);
