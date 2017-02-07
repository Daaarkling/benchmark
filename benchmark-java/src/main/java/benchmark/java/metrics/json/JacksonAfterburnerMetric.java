package benchmark.java.metrics.json;

import com.fasterxml.jackson.databind.ObjectMapper;
import com.fasterxml.jackson.module.afterburner.AfterburnerModule;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class JacksonAfterburnerMetric extends AMetric {
	
	private ObjectMapper mapper;
	private final Info info = new Info(Config.Format.JSON, "JacksonAfterburner", "https://github.com/FasterXML/jackson-modules-base/tree/master/afterburner", "2.8.5");

	
	@Override
	public Info getInfo() {
		return info;
	}
	
	
	@Override
	protected void prepareBenchmark() {
		mapper = new ObjectMapper();
		mapper.registerModule(new AfterburnerModule());
	}


	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		mapper.writeValue(output, data);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = mapper.readValue(input, PersonCollection.class);
		return personCollection;
	}
}
