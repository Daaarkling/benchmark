
package benchmark.java.output;

import benchmark.java.metrics.MetricResult;
import java.util.List;
import java.util.Map;


public interface IOutputHandler {
	

	public void handleBenchmarkResult(List<MetricResult> result);


	public void printBenchmarkInfo(Map<String, String> info);
}
