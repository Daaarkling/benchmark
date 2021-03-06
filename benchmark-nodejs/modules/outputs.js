var json2csv = require('json2csv');
var fs = require('fs');
var path = require('path');
var Table = require('cli-table');
var metric = require('./metrics/metric');



exports.csv = function(result, config) {
	
	var trans = transformResult(result, config.outer);
	var transSum = transformResultSummarize(result);

	printCsv(path.join(config.outDir, "nodejs-serialize.csv"), trans.headersSerialize, trans.rowsSerialize);
	printCsv(path.join(config.outDir, "nodejs-deserialize.csv"), trans.headersDeserialize, trans.rowsDeserialize);
	printCsv(path.join(config.outDir, "nodejs-summarize.csv"), transSum.headers, transSum.rows);
	
	var infoString = "NODEJS version: " + config.version +
					"\nTest data size (raw): " + config.size +
					"\nOuter repetition: " + config.outer +
					"\nInner repetition: " + config.inner +
					"\nDate: " + new Date().toISOString();
	
	fs.writeFile(path.join(config.outDir, 'nodejs-info.txt'), infoString, function (err) {
  		if (err) throw error;
	});
}


exports.console = function(result, config) {

	var trans = transformResult(result, config.outer);
	var transSum = transformResultSummarize(result);
	
	printTable("nodejs serialize", trans.headersSerialize, trans.rowsSerialize);
	printTable("nodejs deserialize", trans.headersDeserialize, trans.rowsDeserialize);
	printTable("nodejs summarize", transSum.headers, transSum.rows);
	
	console.log("\nBenchmark info\n");
	console.log("NODEJS version: " + config.version);
	console.log("Test data size (raw): " + config.size);
	console.log("Outer repetition: " + config.outer);
	console.log("Inner repetition: " + config.inner);
	console.log("Date: " + new Date().toISOString() + "\n");
}



var transformResult = function(result, outer) {
	
	var headersSerialize = [];
	var headersDeserialize = [];
	var rowsSerialize = [];
	var rowsDeserialize = [];
	
	for (var i = 0; i < outer; i++) {
		var rowSerialize = [];
		var rowDeserialize = [];
		result.forEach((item) => {
			if (i == 0) {
				if (item.serialize.length > 0) {
					headersSerialize.push(item.name);
				}
				if (item.deserialize.length > 0) {
					headersDeserialize.push(item.name);
				}
			}
			var sizeSerialize = item.serialize.length;
			if (sizeSerialize > 0 && i < sizeSerialize) {
				rowSerialize.push((item.serialize[i] / 1000000).toFixed(4))
			}
			var sizeDeserialize = item.deserialize.length;
			if (sizeDeserialize > 0 && i < sizeDeserialize) {
				rowDeserialize.push((item.deserialize[i] / 1000000).toFixed(4))
			}
		});
		rowsSerialize.push(rowSerialize);
		rowsDeserialize.push(rowDeserialize);
	}
	
	return {
		headersSerialize: headersSerialize,
		headersDeserialize: headersDeserialize,
		rowsSerialize: rowsSerialize,
		rowsDeserialize: rowsDeserialize
	};
}


var transformResultSummarize = function(result) {
	
	var headers = ['Name', 'Serialize (ms)', 'Deserialize (ms)', 'Size (kB)'];
	var rows = [];
	
	result.forEach((item) => {
		var serializeMean = mean(item.serialize);
		var deserializeMean = mean(item.deserialize)
		rows.push([
			item.name,
			serializeMean !== 0 ? (serializeMean / 1000000).toFixed(4) : 0,
			deserializeMean !== 0 ? (deserializeMean / 1000000).toFixed(4) : 0,
			item.size !== 0 ? (item.size / 1024).toFixed(4) : 0
		]);
	});
	
	return {
		headers: headers,
		rows: rows
	}
}


var mean = function(numbers) {
	if(numbers.length === 0) 
		return 0;
	
	var sum = 0;
	numbers.forEach((number) => {
		sum += number;
	});
	return sum / numbers.length;
}


var printTable = function(name, headers, rows) {

	var chars = {
	  'top': '═', 'top-mid': '╤', 'top-left': '╔', 'top-right': '╗',
	  'bottom': '═', 'bottom-mid': '╧', 'bottom-left': '╚',
	  'bottom-right': '╝', 'left': '║', 'left-mid': '╟', 'mid': '─',
	  'mid-mid': '┼', 'right': '║', 'right-mid': '╢', 'middle': '│'
	};
	
	var table = new Table({
		head: headers,
		chars: chars
	});
	
	rows.forEach((row) => {
		table.push(row);
	});
	
	console.log("\n" + name + "\n");
	console.log(table.toString());
}


var printCsv = function(fileName, headers, rows) {

	var csv = json2csv({ data: rows, fieldNames: headers, del: ";" });
	fs.writeFile(fileName, csv, (err) => {
		if (err) throw err;
	});
}





