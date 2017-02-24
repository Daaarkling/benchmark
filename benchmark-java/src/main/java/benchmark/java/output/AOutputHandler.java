package benchmark.java.output;

import benchmark.java.metrics.MetricResult;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Comparator;
import java.util.List;


public abstract class AOutputHandler implements IOutputHandler {
	
	protected final int outer;

	
	public AOutputHandler(int outer) {
		this.outer = outer;
	}

	
	
	@Override
	public void handleBenchmarkResult(List<MetricResult> result) {
		
		result.sort(new Comparator<MetricResult>() {
			@Override
			public int compare(MetricResult o1, MetricResult o2) {
				return o2.getFullName().compareTo(o1.getFullName());
			}
		});
		
		transformResult(result);
		transformResultSummarize(result);
	}

	
	protected void transformResultSummarize(List<MetricResult> result) {

		List<String> headers = Arrays.asList(new String[]{"Name", "Serialize (ms)", "Deserialize (ms)", "Size (kB)"});
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
		printOutput("java summarize", headers, rows);
	}
	
	protected void transformResult(List<MetricResult> result) {

		List<String> headersSerialize = new ArrayList<>();
		List<String> headersDeserialize = new ArrayList<>();

		List<List<String>> rowsSerialize = new ArrayList<>();
		List<List<String>> rowsDeserialize = new ArrayList<>();

		for (int i = 0; i < outer; i++) {
			List<String> rowSerialize = new ArrayList<>();
			List<String> rowDeserialize = new ArrayList<>();
			for (MetricResult metricResult : result) {
				if (i == 0) {
					if (!metricResult.isSerializeEmpty()) {
						headersSerialize.add(metricResult.getFullName());
					}
					if (!metricResult.isDeserializeEmpty()) {
						headersDeserialize.add(metricResult.getFullName());
					}
				}
				int sizeSerialize = metricResult.getSerialize().size();
				if (sizeSerialize > 0 && i < sizeSerialize) {
					rowSerialize.add(new BigDecimal(metricResult.getSerialize().get(i)).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString());
				}
				int sizeDeserialize = metricResult.getDeserialize().size();
				if (sizeDeserialize > 0 && i < sizeDeserialize) {
					rowDeserialize.add(new BigDecimal(metricResult.getDeserialize().get(i)).divide(new BigDecimal("1000000"), 4, RoundingMode.FLOOR).toString());
				}	
			}
			rowsSerialize.add(rowSerialize);
			rowsDeserialize.add(rowDeserialize);
		}
		
		printOutput("java serialize", headersSerialize, rowsSerialize);
		printOutput("java deserialize", headersDeserialize, rowsDeserialize);
	}
	
	protected abstract void printOutput(String name, List<String> headers, List<List<String>> rows);
}
