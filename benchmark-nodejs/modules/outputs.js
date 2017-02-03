var json2csv = require('json2csv');
var fs = require('fs');
var Table = require('cli-table');

var headers = ['Name', 'Serialize (ms)', 'Deserialize (ms)', 'Size (kB)'];

var format = function(result) {
	result.forEach((item) => {
		item.serialize = (mean(item.serialize) / 1000000).toFixed(4),
		item.deserialize = (mean(item.deserialize) / 1000000).toFixed(4),
		item.size = item.size !== 0 ? (item.size / 1024).toFixed(4) : 0
	});
}

var mean = function(numbers) {
	if(numbers.length) === 0 
		return 0;
	
	var sum = 0;
	numbers.forEach((number) => {
		sum += number;
	});
	return sum / numbers.length;
}


exports.csv = function(result, outDir = "./") {
	
	format(result);
	
	var fields = ['name', 'serialize', 'deserialize', 'size'];

	var csv = json2csv({ data: result, fields: fields, fieldNames: headers, del: ";" });
	fs.writeFile(outDir + 'nodejs-benchmark-output.csv', csv, (err) => {
		if (err) throw err;
		console.log("Benchmark processed successfully!");
	});
}


exports.console = function(result) {

	console.log(print(result));
	console.log("Benchmark processed successfully!");
}


exports.file = function(result, outDir = "./") {

	fs.writeFile(outDir + 'nodejs-benchmark-output.txt', print(result), (err) => {
		if (err) throw err;
		console.log("Benchmark processed successfully!");
	});

}

var print = function(result) {

	format(result);
	
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
	
	result.forEach((item) => {
		table.push([item.name, item.serialize, item.deserialize, item.size]);
	});
	
	return table.toString();
}





