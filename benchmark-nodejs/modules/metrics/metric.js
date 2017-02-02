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

metric.prototype.run = function(testData, repetitions = 1000) {
		
	var result = {
		name: this.fullName(),
		serialize: 0,
		deserialize: 0,
		size: 0
	};
	
	var start, time, diff, serializedData;
	
	testData = this.prepareDataForSerialize(testData)
	
	// Serialize
	if(this.serialize) {
		// warm up
		serializedData = this.serializeImpl(testData);

		// run
		start = process.hrtime();
		for (var i = 1; i <= repetitions; i++) {
			this.serializeImpl(testData);
		}
		diff = process.hrtime(start);
		time = diff[0] * 1e9 + diff[1];
		
		// store result
		result.serialize = time;
		result.size = serializedData.length;
	} else {
		serializedData = this.prepareDataForDeserialize(testData);
		if(!serializedData) {
			return result;
		}
	}
	
	// Deserialize
	if(this.deserialize) {
		// warm up
		this.deserializeImpl(serializedData);

		// run
		start = process.hrtime();
		for (var i = 1; i <= repetitions; i++) {
			this.deserializeImpl(serializedData);
		}
		diff = process.hrtime(start);
		time = diff[0] * 1e9 + diff[1];
		
		// store result
		result.deserialize = time;
	}
	return result;
};


metric.prototype.prepareDataForSerialize = function (testData) {
	
	return testData;	
};


metric.prototype.prepareDataForDeserialize = function (testData) {
	
	return null;	
};


metric.prototype.serializeImpl = function (testData) {
	
	return this.serialize(testData);	
};


metric.prototype.deserializeImpl = function (serializedData) {
	
	return this.deserialize(serializedData);	
};


module.exports = metric;



