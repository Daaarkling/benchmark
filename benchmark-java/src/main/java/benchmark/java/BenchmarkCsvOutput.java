package benchmark.java;

import com.opencsv.CSVWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;


public class BenchmarkCsvOutput extends Benchmark {

	public static String fileName = "java-benchmark-output.csv";
	private final String outputDir;
	
	
	public BenchmarkCsvOutput(Config config, String outputDir) {
		super(config);
		this.outputDir = outputDir;
	}

	
	
	@Override
	protected void handleResult(List<String> headers, List<List<String>> rows) {
		
		try {
			String name = outputDir + File.separator + fileName;
			CSVWriter writer = new CSVWriter(new FileWriter(name), ';');
			String[] entries = new String[headers.size()];
			headers.toArray(entries);
			writer.writeNext(entries);
			
			for (List<String> row : rows) {
				entries = new String[row.size()];
				row.toArray(entries);
				writer.writeNext(entries);
			}
			writer.close();
		} catch (IOException ex) {
			Logger.getLogger(BenchmarkCsvOutput.class.getName()).log(Level.SEVERE, null, ex);
		}
	}
	
	
}
