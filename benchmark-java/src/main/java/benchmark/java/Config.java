package benchmark.java;

import java.io.File;
import java.util.List;
import benchmark.java.metrics.IMetric;
import benchmark.java.utils.MetricClassLoader;


public class Config {

	public enum Format {
		PROTOBUF("protobuf"),
		AVRO("avro"),
		MSGPACK("msgpack"),
		NATIVE("native"),
		JSON("json"),
		XML("xml");

		private final String name;
		
		private Format(String name) {
			this.name = name;
		}
		
		public String getName() {
			return name;
		}
		
		public static Format getByName(String name) {
			for (Format f : Format.values()) {
				if (f.getName().equals(name)) {
					return f;
				}
			}
			return null;
		}
		
		public static String[] toStringArray() {
			String [] array = new String[Format.values().length];
			for (int i = 0; i < Format.values().length; i++) {
				array[i] = Format.values()[i].getName();
			}
			return array;
		}

		@Override
		public String toString() {
			return name;
		}
	};

	public static String testDataPath = "testdata/test_data_small.json";
	
	private List<IMetric> metrics;
	private final File testData;
	private final int repetitions;
	private final Format format;

	private Config(Builder builder) {
		this.testData = builder.testData;
		this.repetitions = builder.repetitions;
		this.format = builder.format;
		findMetrics();
	}

	private void findMetrics() {
		
		MetricClassLoader classLoader = new MetricClassLoader();
		metrics = classLoader.loadMetricClasses();
		if (format != null) {
			metrics.removeIf((item) -> !item.getInfo().getFormat().equals(format));
		}
	}

	public List<IMetric> getMetrics() {
		return metrics;
	}

	public File getTestData() {
		return testData;
	}

	public int getRepetitions() {
		return repetitions;
	}
	

	public static class Builder {
		
		private final File testData;
		private int repetitions = 100;
		private Format format;

		public Builder(File testData) {
			this.testData = testData;
		}

		public Builder repetitions(Integer repetitions) {
			if (repetitions != null && repetitions >= 1) {
				this.repetitions = repetitions;
			}
			return this;
		}
		
		public Builder format(Format format) {

			this.format = format;
			return this;
		}

		public Config build() {
			return new Config(this);
		}
	}
}
