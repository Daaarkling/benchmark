package benchmark.java.metrics;

import java.util.ArrayList;
import java.util.List;



public class MetricResult {
	
	private Info info;
	private int size;
	private List<Long> serialize = new ArrayList<>();
	private List<Long> deserialize = new ArrayList<>();

	public void addSerialize(Long time) {
		serialize.add(time);
	}
	
	public void addDeserialize(Long time) {
		deserialize.add(time);
	}
	
	private double computeMean(List<Long> times) {
		
		if (times.isEmpty())
			return 0;
		
		Long sum = 0l;
		for(Long time : times) {
			sum += time;
		}
		return sum / (double) times.size();
	}
	
	public String getFullName() {
		
		if(info != null) {
			return info.getFullName();
		}
		return "---";
	}

	
	public double getSerializeMean() {		
		return computeMean(serialize);
	}

	public double getDeserializeMean() {
		return computeMean(deserialize);
	}
	
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

	public List<Long> getSerialize() {
		return serialize;
	}

	public void setSerialize(List<Long> serialize) {
		this.serialize = serialize;
	}

	public List<Long> getDeserialize() {
		return deserialize;
	}

	public void setDeserialize(List<Long> deserialize) {
		this.deserialize = deserialize;
	}

	
}
