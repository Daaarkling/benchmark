package benchmark.java.metrics.jnative;

import java.io.InputStream;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import benchmark.java.Config;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;


public class JavaSerializationMetric extends AMetric {
	
	private final Info info = new Info(Config.Format.NATIVE, "Java Serialization", "https://docs.oracle.com/javase/tutorial/jndi/objects/serial.html");
	
	@Override
	public Info getInfo() {
		return info;
	}
	
	@Override
	public boolean serialize(Object data, OutputStream output) throws Exception {
		
		ObjectOutputStream objectOutputStream = new ObjectOutputStream(output);
		objectOutputStream.writeObject(data);
		return true;
	}
	
	@Override
	public Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		ObjectInputStream inputStream = new ObjectInputStream(input);
		PersonCollection personCollection = (PersonCollection) inputStream.readObject();
		return personCollection;
	}
	
	
}
