
package benchmark.java.metrics.json;

import benchmark.java.entities.Friend;
import benchmark.java.entities.Person;
import benchmark.java.entities.PersonCollection;
import com.fasterxml.jackson.core.JsonGenerator;
import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.JsonSerializer;
import com.fasterxml.jackson.databind.SerializerProvider;
import java.io.IOException;


public class JacksonCustomSerializator extends JsonSerializer<PersonCollection> {

	@Override
	public void serialize(PersonCollection t, JsonGenerator jg, SerializerProvider sp) throws IOException, JsonProcessingException {
		
		jg.writeStartObject();
		jg.writeArrayFieldStart("persons");

		for (Person person : t.getPersons()) {
			jg.writeStartObject();
			jg.writeStringField("id", person.getId());
			jg.writeNumberField("index", person.getIndex());
			jg.writeStringField("guid", person.getGuid());
			jg.writeBooleanField("isActive", person.isIsActive());
			jg.writeStringField("balance", person.getBalance());
			jg.writeStringField("picture", person.getPicture());
			jg.writeNumberField("age", person.getAge());
			jg.writeStringField("eyeColor", person.getEyeColor());
			jg.writeStringField("name", person.getName());
			jg.writeStringField("gender", person.getGender());
			jg.writeStringField("company", person.getCompany());
			jg.writeStringField("email", person.getEmail());
			jg.writeStringField("phone", person.getPhone());
			jg.writeStringField("address", person.getAddress());
			jg.writeStringField("about", person.getAbout());
			jg.writeStringField("registered", person.getRegistered());
			jg.writeNumberField("latitude", person.getLatitude());
			jg.writeNumberField("longitude", person.getLongitude());
			
			jg.writeArrayFieldStart("tags");
			for(String tag : person.getTags()) {
				jg.writeString(tag);
			}
			jg.writeEndArray();
				
			jg.writeArrayFieldStart("friends");
			for (Friend friend : person.getFriends()) {
				jg.writeStartObject();
				jg.writeNumberField("id", friend.getId());
				jg.writeStringField("name", friend.getName());
				jg.writeEndObject();
			}
			jg.writeEndArray();
			
			jg.writeStringField("greeting", person.getGreeting());
			jg.writeStringField("favoriteFruit", person.getFavoriteFruit());
			
			jg.writeEndObject();
		}
		jg.writeEndArray();
		jg.writeEndObject();
	}
	
	
}
