package benchmark.java.metrics.xml;

import com.thoughtworks.xstream.XStream;
import java.io.InputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.Friend;
import benchmark.java.entities.Person;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class XStreamMetric extends AMetric {
	
	private XStream xstream;

	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.XML, "XStream", "https://github.com/x-stream/xstream", "1.4.9");
	}
	
	
	@Override
	protected void prepareBenchmark() {
		
		xstream = new XStream();
		xstream.alias("personCollection", PersonCollection.class);
		xstream.alias("person", Person.class);
		xstream.alias("friend", Friend.class);
	}

	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		xstream.toXML(data, output);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = (PersonCollection) xstream.fromXML(input);
		return personCollection;
	}
	
	
}
