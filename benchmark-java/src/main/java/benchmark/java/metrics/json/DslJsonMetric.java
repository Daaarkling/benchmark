package benchmark.java.metrics.json;

import com.dslplatform.json.DslJson;
import com.dslplatform.json.JsonWriter;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;

public class DslJsonMetric extends AMetric {

	private JsonWriter writer;
	private DslJson<Object> dslJson;

	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "dsl-json", "https://github.com/ngs-doo/dsl-json", "1.3.3");
	}

	@Override
	protected void prepareBenchmark() {

		dslJson = new DslJson<>();
		writer = dslJson.newWriter();
	}

	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		dslJson.serialize(writer, data);
		writer.toStream(output);
		writer.reset();
		return true;
	}

	

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = dslJson.deserialize(PersonCollection.class, bytes, bytes.length);
		return personCollection;
	}
}
