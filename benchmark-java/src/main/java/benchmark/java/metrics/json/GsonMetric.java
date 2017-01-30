package benchmark.java.metrics.json;

import com.google.gson.Gson;
import com.google.gson.stream.JsonReader;
import com.google.gson.stream.JsonWriter;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class GsonMetric extends AMetric {
	
	private Gson gson;

	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "Gson", "https://github.com/google/gson", "2.8.0");
	}
	
	
	@Override
	protected void prepareBenchmark() {
		gson = new Gson();
	}

	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		JsonWriter writer =  new JsonWriter(new OutputStreamWriter(output));	
		gson.toJson(data, PersonCollection.class, writer);
		writer.close();
		return true;
	}

	
	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		JsonReader reader = new JsonReader(new InputStreamReader(input));
		PersonCollection pC = gson.fromJson(reader, PersonCollection.class);
		reader.close();
		return pC;
	}
}
