package benchmark.java;


import java.io.File;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import benchmark.java.converters.IDataConverter;
import benchmark.java.converters.PojoConverter;
import benchmark.java.metrics.IMetric;
import benchmark.java.metrics.MetricResult;
import benchmark.java.utils.Formatters;
import java.util.Set;
import java.util.concurrent.Callable;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.concurrent.Future;
import java.util.logging.Level;
import java.util.logging.Logger;


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
		transformResult(result);
	}
	
	protected void transformResult(List<MetricResult> result) {
		
		List<String> headers = Arrays.asList(new String[]{"Name", "Serialize", "Deserialize", "Size"});
		List<List<String>> rows = new ArrayList<>();

		for (MetricResult metricResult : result) {
			List<String> row = new ArrayList<>();
			// pretty format of times and size
			row.add(metricResult.getInfo().getFullName());
			row.add(metricResult.getSerialize() != 0 ? Formatters.seconds(metricResult.getSerialize()) : "---");
			row.add(metricResult.getDeserialize() != 0 ? Formatters.seconds(metricResult.getDeserialize()) : "---");
			row.add(metricResult.getSerialize() != 0 ? Formatters.bytes(metricResult.getSize()) : "---");	
			rows.add(row);
		}
		
		handleResult(headers, rows);
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
