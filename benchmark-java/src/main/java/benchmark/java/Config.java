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
	private final int inner;
	private final int outer;
	private final Format format;

	private Config(Builder builder) {
		this.testData = builder.testData;
		this.inner = builder.inner;
		this.outer = builder.outer;
		this.format = builder.format;
		findMetrics();
	}

	private void findMetrics() {
		
		MetricClassLoader classLoader = new MetricClassLoader();
		metrics = classLoader.loadMetricClasses();
		if (format != null) {
			metrics.removeIf((item) -> !item.getInfo().getFormat().equals(format));
		}
		//metrics.removeIf((item) -> !item.getInfo().getName().equals("LoganSquare") && !item.getInfo().getName().equals("dsl-json"));
		//metrics.removeIf((item) -> !item.getInfo().getName().equals("LoganSquare"));
	}

	public static Builder newBuilder(File testData) {
		
		return new Builder(testData);
	}
	
	public List<IMetric> getMetrics() {
		return metrics;
	}

	public File getTestData() {
		return testData;
	}

	public int getInner() {
		return inner;
	}

	public int getOuter() {
		return outer;
	}

	public Format getFormat() {
		return format;
	}

	public static String getTestDataPath() {
		return testDataPath;
	}


	public static final class Builder {
		
		private final File testData;
		private int inner = 100;
		private int outer = 30;
		private Format format;

		public Builder(File testData) {
			this.testData = testData;
		}

		public Builder inner(Integer inner) {
			if (inner != null && inner >= 1) {
				this.inner = inner;
			}
			return this;
		}
		
		public Builder outer(Integer outer) {
			if (outer != null && outer >= 1) {
				this.outer = outer;
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
