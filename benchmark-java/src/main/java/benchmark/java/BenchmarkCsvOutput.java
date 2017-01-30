package benchmark.java;

import benchmark.java.metrics.MetricResult;
import com.opencsv.CSVWriter;
import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.util.ArrayList;
import java.util.Arrays;
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
	protected void transformResult(List<MetricResult> result) {

		List<String> headers = Arrays.asList(new String[]{"Name", "Serialize (ms)", "Deserialize (ms)", "Size (B)"});
		List<List<String>> rows = new ArrayList<>();

		for (MetricResult metricResult : result) {
			List<String> row = new ArrayList<>();
			// nanoseconds convert to miliseconds
			row.add("java - " + metricResult.getInfo().getFullName());
			row.add(new BigDecimal(metricResult.getSerialize()).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString());
			row.add(new BigDecimal(metricResult.getDeserialize()).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString());
			row.add(String.valueOf(metricResult.getSize()));
			rows.add(row);
		}
		handleResult(headers, rows);
	}
	
	
	@Override
	protected void handleResult(List<String> headers, List<List<String>> rows) {
		
		headers = Arrays.asList(new String[]{"Name", "Serialize (ms)", "Deserialize (ms)", "Size (B)"});
		
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
