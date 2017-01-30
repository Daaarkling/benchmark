package benchmark.java.metrics.json;

import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.serializer.SerializerFeature;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class FastjsonMetric extends AMetric {
	
	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.JSON, "Fastjson", "https://github.com/alibaba/fastjson", "1.2.23");
	}
	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		JSON.writeJSONString(output, data, SerializerFeature.EMPTY);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = JSON.parseObject(input, PersonCollection.class);
		return personCollection;
	}
}
