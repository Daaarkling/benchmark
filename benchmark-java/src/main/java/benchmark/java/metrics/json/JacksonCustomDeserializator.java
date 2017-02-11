
package benchmark.java.metrics.json;

import benchmark.java.entities.Friend;
import benchmark.java.entities.Person;
import benchmark.java.entities.PersonCollection;
import com.fasterxml.jackson.core.JsonParser;
import com.fasterxml.jackson.core.JsonProcessingException;
import com.fasterxml.jackson.databind.DeserializationContext;
import com.fasterxml.jackson.databind.JsonDeserializer;
import com.fasterxml.jackson.databind.JsonNode;
import java.io.IOException;


public class JacksonCustomDeserializator extends JsonDeserializer<PersonCollection> {

	@Override
	public PersonCollection deserialize(JsonParser jp, DeserializationContext dc) throws IOException, JsonProcessingException {
		
		PersonCollection personCollection = new PersonCollection();
		
		JsonNode node = jp.getCodec().readTree(jp);
		
		for(JsonNode personNode : node.get("persons")) {
			Person person = new Person();
			person.setId(personNode.get("id").asText());
			person.setIndex(personNode.get("index").asInt());
			person.setGuid(personNode.get("guid").asText());
			person.setIsActive(personNode.get("isActive").asBoolean());
			person.setBalance(personNode.get("balance").asText());
			person.setPicture(personNode.get("picture").asText());
			person.setAge(personNode.get("age").asInt());
			person.setEyeColor(personNode.get("eyeColor").asText());
			person.setName(personNode.get("name").asText());
			person.setGender(personNode.get("gender").asText());
			person.setCompany(personNode.get("company").asText());
			person.setEmail(personNode.get("email").asText());
			person.setPhone(personNode.get("phone").asText());
			person.setAddress(personNode.get("address").asText());
			person.setAbout(personNode.get("about").asText());
			person.setRegistered(personNode.get("registered").asText());
			person.setLatitude((float)personNode.get("latitude").asDouble());
			person.setLongitude((float)personNode.get("longitude").asDouble());
			
			for (JsonNode tagNode : personNode.get("tags")) {
				person.addTag(tagNode.asText());
			}

			for (JsonNode friendNode : personNode.get("friends")) {
				Friend friend = new Friend();
				friend.setId(friendNode.get("id").asInt());
				friend.setName(friendNode.get("name").asText());
				person.addFriend(friend);
			}

			person.setGreeting(personNode.get("greeting").asText());
			person.setFavoriteFruit(personNode.get("favoriteFruit").asText());

			personCollection.addPerson(person);
		}
				
		return personCollection;
	}
}
