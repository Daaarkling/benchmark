package benchmark.java.metrics.json;


import java.io.InputStream;
import java.io.OutputStream;
import org.apache.johnzon.mapper.Mapper;
import org.apache.johnzon.mapper.MapperBuilder;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class JohnzonMetric extends AMetric {
	
	private Mapper mapper;

	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "Johnzon", "http://johnzon.apache.org/", "1.0.0");
	}
	
	
	@Override
	protected void prepareBenchmark() {
		mapper = new MapperBuilder().build();
	}
	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		mapper.writeObject(data, output);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = mapper.readObject(input, PersonCollection.class);
		return personCollection;
	}
}
