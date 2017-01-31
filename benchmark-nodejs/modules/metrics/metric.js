
exports.Metric = function (format, name, version, url, serialize, deserialize) {
	
	this.format = format;
	this.name = name;
	this.version = version;
	this.serialize = serialize;
	this.deserialize = deserialize;
	
	
	this.fullName = function() {
		
		if(this.version) {
			return this.format + " - " + this.name + " - " + this.version;
		}
		return this.format + " - " + this.name;
	}
	
	
	this.run = function(testdata, repetitions = 1000) {
		
		var result = {
			name: this.fullName(),
			serialize: 0,
			deserialize: 0,
			size: 0
		};
		
		var start, time, serializedData;
	
		// Serialize
		// warm up
		serializedData = serialize(testdata);
	
		start = process.hrtime();
		for (var i = 1; i <= repetitions; i++) {
			serialize(testdata);
		}
		time = process.hrtime(start)[1];
		
		result.serialize = time;
		result.size = serializedData.length;
		
		// Deserialize
		// warm up
		deserialize(serializedData);
	
		start = process.hrtime();
		for (var i = 1; i <= repetitions; i++) {
			deserialize(serializedData);
		}
		time = process.hrtime(start)[1];
		
		result.deserialize = time;
		
		return result;
	}
}
