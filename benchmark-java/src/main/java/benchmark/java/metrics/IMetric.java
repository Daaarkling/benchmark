package benchmark.java.metrics;

import java.io.File;


public interface IMetric {
	
	public Info getInfo();
	
	public MetricResult run(Object testData, File testDataFile, int inner, int outer);
}
