package benchmark.java.metrics;

import java.io.File;
import java.io.InputStream;
import java.io.OutputStream;


public interface IMetric {
	
	public Info getInfo();
	
	/**
	 * Method for run the benchmark, should execute serialize() and deserialize() methods and deliver result
	 * 
	 * @param testData
	 * @param testDataFile
	 * @param repetitions
	 * @return object that holds results
	 */
	public MetricResult run(Object testData, File testDataFile, int repetitions);

	public boolean serialize(Object data, OutputStream output) throws Exception;

	public Object deserialize(InputStream input, byte[] bytes) throws Exception;
}
