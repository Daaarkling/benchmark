var moment = require("moment");


exports.startMessage = function (name) {

	var timestamp = getCurrentTimestamp();
	console.log("[INFO] " + timestamp + " Benchmark of \"" + name + "\" has started.");
};

exports.endMessage = function (name) {

	var timestamp = getCurrentTimestamp();
	console.log("[INFO] " + timestamp + " Benchmark of \"" + name + "\" has finished.");
};

var getCurrentTimestamp = function () {

	return moment().format('YYYY-MM-DD HH:mm:ss');
};
