package benchmark.java;


import java.io.File;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import benchmark.java.converters.IDataConverter;
import benchmark.java.converters.PojoConverter;
import benchmark.java.metrics.IMetric;
import benchmark.java.metrics.MetricResult;
import java.math.BigDecimal;
import java.math.RoundingMode;


public abstract class Benchmark {
	
	protected Config config;

	
	public Benchmark(Config config) {
		
		this.config = config;
	}
	
	/**
	 * Run benchmark
	 */
	public void run() {
		
		Object data = prepareData();
		List<MetricResult> result = new ArrayList<>();
		

		for (IMetric metric : config.getMetrics()) {
			// run metric benchmark
			MetricResult metricResult = metric.run(data, config.getTestData(), config.getRepetitions());
			if (metricResult != null) {
				result.add(metricResult);
			}
		}
		result.sort((p1, p2) -> p1.getInfo().getFullName().compareTo(p2.getInfo().getFullName()));
		
		List<List<String>> rows = transformResult(result);
		List<String> headers = Arrays.asList(new String[]{"Name", "Serialize (ms)", "Deserialize (ms)", "Size (kB)"});
		handleResult(headers, rows);
	}
	
	protected List<List<String>> transformResult(List<MetricResult> result) {
		
		List<List<String>> rows = new ArrayList<>();

		for (MetricResult metricResult : result) {
			List<String> row = new ArrayList<>();
			
			double serializeMean = metricResult.getSerializeMean();
			double deserializeMean = metricResult.getDeserializeMean();
			double size = metricResult.getSize();
			
			row.add(metricResult.getFullName());
			row.add(serializeMean != 0 ? new BigDecimal(serializeMean).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString() : "0");
			row.add(deserializeMean != 0 ? new BigDecimal(deserializeMean).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString() : "0");
			row.add(size != 0 ? new BigDecimal(size).divide(new BigDecimal("1024"), 4, RoundingMode.FLOOR).toString() : "0");	
			rows.add(row);
		}
		return rows;
	}
	
	protected abstract void handleResult(List<String> headers, List<List<String>> rows);
	
	
	protected Object prepareData() {
		File testData = config.getTestData();
		IDataConverter convertor = new PojoConverter();
		return convertor.convertData(testData);
	}

	public Config getConfig() {
		return config;
	}

	public void setConfig(Config config) {
		this.config = config;
	}
}
