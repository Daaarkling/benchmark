
exports.repetitions = function (value) {
	return parseInt(value) && value > 0;
}

exports.format = function valFormat(value, formats) {
	return formats.includes(value);
}
