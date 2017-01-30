package benchmark.java.metrics.json;

import com.cedarsoftware.util.io.JsonReader;
import com.cedarsoftware.util.io.JsonWriter;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class JsonIOMetric extends AMetric {
	

	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "Json-io", "https://github.com/jdereg/json-io", "4.9.5");
	}
	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		JsonWriter jsonWriter = new JsonWriter(output);
		jsonWriter.write(data);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = (PersonCollection) JsonReader.jsonToJava(input, null);
		return personCollection;
	}
}
