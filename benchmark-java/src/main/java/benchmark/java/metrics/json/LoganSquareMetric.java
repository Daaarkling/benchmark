package benchmark.java.metrics.json;


import com.bluelinelabs.logansquare.LoganSquare;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class LoganSquareMetric extends AMetric {
	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "LoganSquare", "https://github.com/bluelinelabs/LoganSquare", "1.3.7");
	}
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		LoganSquare.serialize(data, output);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = LoganSquare.parse(input, PersonCollection.class);
		return personCollection;
	}
}
