var lib = require('data2xml')({});


exports.convert = function (testData) {

	return lib("data", testData);
};
