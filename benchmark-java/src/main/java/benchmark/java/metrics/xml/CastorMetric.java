package benchmark.java.metrics.xml;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import org.exolab.castor.xml.Marshaller;
import org.exolab.castor.xml.Unmarshaller;
import org.exolab.castor.xml.XMLContext;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class CastorMetric extends AMetric {
	
	private Marshaller marshaller;
	private Unmarshaller unmarshaller;

	
	@Override
	public Info getInfo() {
		return new Info(Config.Format.XML, "Castor", "https://github.com/castor-data-binding/castor", "1.4.1");
	}
	
	
	@Override
	protected void prepareBenchmark() {
		
		XMLContext context = new XMLContext();
		marshaller = context.createMarshaller();
		unmarshaller = context.createUnmarshaller();
		unmarshaller.setClass(PersonCollection.class);
	}
	
	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		marshaller.setWriter(new OutputStreamWriter(output));
		marshaller.marshal(data);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = (PersonCollection) unmarshaller.unmarshal(new InputStreamReader(input));
		return personCollection;
	}
	
	
}
