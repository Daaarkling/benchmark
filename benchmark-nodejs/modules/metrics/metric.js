var metric = function(format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
}

metric.prototype.fullName = function() {
		
	if(this.version) {
		return this.format + " - " + this.name + " - " + this.version;
	}
	return this.format + " - " + this.name;
};

metric.prototype.run = function(testdata, repetitions = 1000) {
		
	var result = {
		name: this.fullName(),
		serialize: 0,
		deserialize: 0,
		size: 0
	};
	
	var start, time, diff, serializedData;

	// Serialize
	// warm up
	serializedData = this.serializeImpl(testdata);

	start = process.hrtime();
	for (var i = 1; i <= repetitions; i++) {
		this.serializeImpl(testdata);
	}
	diff = process.hrtime(start);
	time = diff[0] * 1e9 + diff[1];
	
	result.serialize = time;
	result.size = serializedData.length;
	
	// Deserialize
	if(!this.deserialize) {
		return result;
	}
	// warm up
	this.deserialize(serializedData);

	start = process.hrtime();
	for (var i = 1; i <= repetitions; i++) {
		this.deserialize(serializedData);
	}
	diff = process.hrtime(start);
	time = diff[0] * 1e9 + diff[1];
	
	result.deserialize = time;
	
	return result;
};

metric.prototype.serializeImpl = function (testData) {
	
	return this.serialize(testData);	
};

module.exports = metric;



