package benchmark.java;


import java.io.File;
import java.util.ArrayList;
import java.util.List;
import benchmark.java.converters.IDataConverter;
import benchmark.java.converters.PojoConverter;
import benchmark.java.metrics.IMetric;
import benchmark.java.metrics.MetricResult;
import benchmark.java.output.IOutputHandler;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.LinkedHashMap;
import java.util.Map;
import java.util.TimeZone;


public class Benchmark {
	
	protected Config config;
	private IOutputHandler outputHandler;

	
	public Benchmark(Config config, IOutputHandler outputHandler) {
		
		this.config = config;
		this.outputHandler = outputHandler;
	}
	
	/**
	 * Run benchmark
	 */
	public void run() {
		
		Object data = prepareData();
		List<MetricResult> result = new ArrayList<>();
		
		for (IMetric metric : config.getMetrics()) {
			// run metric benchmark
			MetricResult metricResult = metric.run(data, config.getTestData(), config.getInner(), config.getOuter());
			if (metricResult != null) {
				result.add(metricResult);
			}
		}
		
		outputHandler.handleBenchmarkResult(result);
		outputHandler.printBenchmarkInfo(getInfo());
	}
	
	public Map<String, String> getInfo() {
		
		TimeZone tz = TimeZone.getTimeZone("UTC");
		DateFormat df = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm'Z'");
		df.setTimeZone(tz);
		String nowAsISO = df.format(new Date());
		
		Map<String, String> info = new LinkedHashMap<>();
		info.put("JAVA version", System.getProperty("java.version"));
		info.put("Test data size (raw)", new BigDecimal(config.getTestData().length()).divide(new BigDecimal("1024"), 2, RoundingMode.FLOOR).toString() + " (kB)");
		info.put("Outer repetition", String.valueOf(config.getOuter()));
		info.put("Inner repetition", String.valueOf(config.getInner()));
		info.put("Date", nowAsISO);
		return info;
	}

	
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

	public IOutputHandler getOutputHandler() {
		return outputHandler;
	}

	public void setOutputHandler(IOutputHandler outputHandler) {
		this.outputHandler = outputHandler;
	}
}
