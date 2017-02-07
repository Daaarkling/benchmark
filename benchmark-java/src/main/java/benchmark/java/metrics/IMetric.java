package benchmark.java.metrics;

import java.io.File;


public interface IMetric {
	
	public static final int OUTER_REPETITION = 10;
	
	public Info getInfo();
	
	public MetricResult run(Object testData, File testDataFile, int repetitions);
}
