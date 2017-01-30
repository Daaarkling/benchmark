package benchmark.java.metrics;



public class MetricResult {
	
	private Info info;
	private int size;
	private long serialize;
	private long deserialize;

	
	
	public Info getInfo() {
		return info;
	}

	public void setInfo(Info info) {
		this.info = info;
	}

	public int getSize() {
		return size;
	}

	public void setSize(int size) {
		this.size = size;
	}

	public long getSerialize() {
		return serialize;
	}

	public void setSerialize(long serialize) {
		this.serialize = serialize;
	}

	public long getDeserialize() {
		return deserialize;
	}

	public void setDeserialize(long deserialize) {
		this.deserialize = deserialize;
	}
}
