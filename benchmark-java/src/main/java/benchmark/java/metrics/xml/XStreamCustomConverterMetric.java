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
import com.thoughtworks.xstream.converters.Converter;
import com.thoughtworks.xstream.converters.MarshallingContext;
import com.thoughtworks.xstream.converters.UnmarshallingContext;
import com.thoughtworks.xstream.io.HierarchicalStreamReader;
import com.thoughtworks.xstream.io.HierarchicalStreamWriter;


public class XStreamCustomConverterMetric extends AMetric implements Converter {
	
	private XStream xstream;
	private final Info info = new Info(Config.Format.XML, "XStream Custom", "https://github.com/x-stream/xstream", "1.4.9");


	
	@Override
	public Info getInfo() {
		return info;
	}
	
	
	@Override
	protected void prepareBenchmark() {
		
		xstream = new XStream();
		xstream.registerConverter(this);
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

	@Override
	public boolean canConvert(Class clazz) {
		return clazz.equals(PersonCollection.class);
	}
	
	
	@Override
	public void marshal(Object source, HierarchicalStreamWriter writer, MarshallingContext context) {
		
		PersonCollection personCollection = (PersonCollection) source;
		
		for(Person person : personCollection.getPersons()) {
			writer.startNode("person");
			writer.startNode("id");
			writer.setValue(person.getId());
			writer.endNode();
			writer.startNode("index");
			writer.setValue(String.valueOf(person.getIndex()));
			writer.endNode();
			writer.startNode("guid");
			writer.setValue(person.getGuid());
			writer.endNode();
			writer.startNode("isActive");
			writer.setValue(String.valueOf(person.isIsActive()));
			writer.endNode();
			writer.startNode("balance");
			writer.setValue(person.getBalance());
			writer.endNode();
			writer.startNode("picture");
			writer.setValue(person.getPicture());
			writer.endNode();
			writer.startNode("age");
			writer.setValue(String.valueOf(person.getAge()));
			writer.endNode();
			writer.startNode("eyeColor");
			writer.setValue(person.getEyeColor());
			writer.endNode();
			writer.startNode("name");
			writer.setValue(person.getName());
			writer.endNode();
			writer.startNode("gender");
			writer.setValue(person.getGender());
			writer.endNode();
			writer.startNode("company");
			writer.setValue(person.getCompany());
			writer.endNode();
			writer.startNode("email");
			writer.setValue(person.getEmail());
			writer.endNode();
			writer.startNode("phone");
			writer.setValue(person.getPhone());
			writer.endNode();
			writer.startNode("address");
			writer.setValue(person.getAddress());
			writer.endNode();
			writer.startNode("about");
			writer.setValue(person.getAbout());
			writer.endNode();
			writer.startNode("registered");
			writer.setValue(person.getRegistered());
			writer.endNode();
			writer.startNode("latitude");
			writer.setValue(String.valueOf(person.getLatitude()));
			writer.endNode();
			writer.startNode("longitude");
			writer.setValue(String.valueOf(person.getLongitude()));
			writer.endNode();
			
			writer.startNode("tags");
			for (String tag : person.getTags()) {
				writer.startNode("tag");
				writer.setValue(tag);
				writer.endNode();
			}
			writer.endNode();
			
			writer.startNode("friends");
			for (Friend friend : person.getFriends()) {
				writer.startNode("friend");
				writer.startNode("id");
				writer.setValue(String.valueOf(friend.getId()));
				writer.endNode();
				writer.startNode("name");
				writer.setValue(friend.getName());
				writer.endNode();
				writer.endNode();
			}
			writer.endNode();
			
			writer.startNode("greeting");
			writer.setValue(person.getGreeting());
			writer.endNode();
			writer.startNode("favoriteFruit");
			writer.setValue(person.getFavoriteFruit());
			writer.endNode();
			
			writer.endNode();
		}
	}

	@Override
	public Object unmarshal(HierarchicalStreamReader reader, UnmarshallingContext context) {
		
		PersonCollection personCollection = new PersonCollection();
		while (reader.hasMoreChildren()) {
			reader.moveDown();
			
			Person person = new Person();
		
			reader.moveDown();
			person.setId(reader.getValue());
			reader.moveUp();
				
			reader.moveDown();
			person.setIndex(Integer.parseInt(reader.getValue()));
			reader.moveUp();
			
			reader.moveDown();
			person.setGuid(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setIsActive(Boolean.parseBoolean(reader.getValue()));
			reader.moveUp();
			
			reader.moveDown();
			person.setBalance(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setPicture(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setAge(Integer.parseInt(reader.getValue()));
			reader.moveUp();
			
			reader.moveDown();
			person.setEyeColor(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setName(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setGender(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setCompany(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setEmail(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setPhone(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setAddress(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setAbout(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setRegistered(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setLatitude(Float.parseFloat(reader.getValue()));
			reader.moveUp();
			
			reader.moveDown();
			person.setLongitude(Float.parseFloat(reader.getValue()));
			reader.moveUp();
			
			reader.moveDown();
			while (reader.hasMoreChildren()) {
				reader.moveDown();
				person.addTag(reader.getValue());
				reader.moveUp();
			}
			reader.moveUp();
			
			reader.moveDown();
			while (reader.hasMoreChildren()) {
				reader.moveDown();
				Friend frined = new Friend();
				
				reader.moveDown();
				frined.setId(Integer.parseInt(reader.getValue()));
				reader.moveUp();
				
				reader.moveDown();
				frined.setName(reader.getValue());
				reader.moveUp();

				person.addFriend(frined);
				reader.moveUp();
			}
			reader.moveUp();
			
			reader.moveDown();
			person.setGreeting(reader.getValue());
			reader.moveUp();
			
			reader.moveDown();
			person.setFavoriteFruit(reader.getValue());
			reader.moveUp();
			
			personCollection.addPerson(person);
			reader.moveUp();
		}
		return personCollection;
	}
	
	
}
