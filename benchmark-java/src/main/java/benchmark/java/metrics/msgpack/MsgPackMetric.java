package benchmark.java.metrics.msgpack;

import com.fasterxml.jackson.databind.ObjectMapper;
import java.io.InputStream;
import java.io.OutputStream;
import org.msgpack.jackson.dataformat.MessagePackFactory;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;

public class MsgPackMetric extends AMetric {
	
	private ObjectMapper objectMapper;

	@Override
	public Info getInfo() {
		return new Info(Config.Format.MSGPACK, "MsgPack", "https://github.com/msgpack/msgpack-java", "0.8.11");
	}
	
	@Override
	protected void prepareBenchmark() {
		objectMapper = new ObjectMapper(new MessagePackFactory());
	}

		
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		objectMapper.writeValue(output, data);
		return true;
	}
	
	
	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		return objectMapper.readValue(input, PersonCollection.class);
	}

	
	
	
}
