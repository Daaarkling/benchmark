package benchmark.java.metrics.xml;

import java.io.InputStream;
import java.io.OutputStream;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class JaxbMetric extends AMetric {
	
	private Marshaller marshaller;
	private Unmarshaller unmarshaller;
	private final Info info = new Info(Config.Format.XML, "JAXB", "https://jaxb.java.net/");

	
	
	@Override
	public Info getInfo() {
		return info;
	}
	

	@Override
	protected void prepareBenchmark() {
		
		try {
			JAXBContext jaxbContext = JAXBContext.newInstance(PersonCollection.class);
			marshaller = jaxbContext.createMarshaller();
			unmarshaller = jaxbContext.createUnmarshaller();
		} catch (JAXBException ex) {
			Logger.getLogger(JaxbMetric.class.getName()).log(Level.SEVERE, null, ex);
		}
	}

	
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {

		marshaller.marshal(data, output);
		return true;
	}

	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		PersonCollection personCollection = (PersonCollection) unmarshaller.unmarshal(input);
		return personCollection;
	}
	
	
}
