var Metric = require('../metric'); 
var lib = require('msgpack-js');


module.exports = new Metric(
		"msgpack",
		"creationix/msgpack-js", 
		"0.3.0", 
		"https://github.com/creationix/msgpack-js", 
		lib.encode, 
		lib.decode
	);
