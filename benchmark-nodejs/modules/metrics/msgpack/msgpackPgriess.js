var Metric = require('../metric'); 
var lib = require('msgpack');


module.exports = new Metric(
		"msgpack",
		"pgriess/node-msgpack", 
		"1.0.2", 
		"https://github.com/pgriess/node-msgpack", 
		lib.pack, 
		lib.unpack
	);
