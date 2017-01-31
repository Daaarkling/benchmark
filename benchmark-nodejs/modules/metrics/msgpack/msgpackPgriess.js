var metric = require('../metric'); 
var lib = require('msgpack');


exports.metric = new metric.Metric(
		"msgpack",
		"pgriess/node-msgpack", 
		"1.0.2", 
		"https://github.com/pgriess/node-msgpack", 
		lib.pack, 
		lib.unpack
	);
