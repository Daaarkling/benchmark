package benchmark.java.converters;

import com.fasterxml.jackson.databind.JsonNode;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import benchmark.java.metrics.avro.EyeColor;
import benchmark.java.metrics.avro.Friend;
import benchmark.java.metrics.avro.Fruit;
import benchmark.java.metrics.avro.Gender;
import benchmark.java.metrics.avro.Person;
import benchmark.java.metrics.avro.PersonCollection;
import benchmark.java.utils.JsonLoader;


public class AvroConverter implements IDataConverter {
	
	/**
	 * 
	 * @param testDataFile
	 * @return PersonCollection Avro object
	 */
	@Override
	public Object convertData(File testDataFile) {
		
		try {
			JsonNode testData = JsonLoader.loadResource(testDataFile);
			
			List<Person> persons = new ArrayList<>();
			
			for (JsonNode personNode : testData.path("persons")) {
				persons.add(parsePerson(personNode));
			}
			
			PersonCollection personCollection = PersonCollection.newBuilder()
					.setPersons(persons)
					.build();
			
			return personCollection;
			
		} catch (IOException ex) {
			Logger.getLogger(AvroConverter.class.getName()).log(Level.SEVERE, null, ex);
			return null;
		}
		
	}
	
	private Person parsePerson(JsonNode personNode) {
		
		Person person = Person.newBuilder()
				.setId(personNode.path("_id").asText())
				.setIndex(personNode.path("index").asInt())
				.setGuid(personNode.path("guid").asText())
				.setIsActive(personNode.path("isActive").asBoolean())
				.setBalance(personNode.path("balance").asText())
				.setPicture(personNode.path("picture").asText())
				.setAge(personNode.path("age").asInt())
				.setEyeColor(parseEyeColor(personNode.path("eyeColor")))
				.setName(personNode.path("name").asText())
				.setGender(parseGender(personNode.path("gender")))
				.setCompany(personNode.path("company").asText())
				.setEmail(personNode.path("email").asText())
				.setPhone(personNode.path("phone").asText())
				.setAddress(personNode.path("address").asText())
				.setAbout(personNode.path("about").asText())
				.setRegistered(personNode.path("registered").asText())
				.setLatitude((float) personNode.path("latitude").asDouble())
				.setLongitude((float) personNode.path("longitude").asDouble())
				.setTags(parseTags(personNode.path("tags")))
				.setFriends(parseFriends(personNode.path("friends")))
				.setGreeting(personNode.path("greeting").asText())
				.setFavoriteFruit(parseFruits(personNode.path("favoriteFruit")))
				.build();
		
		return person;
	}
	
	
	private EyeColor parseEyeColor(JsonNode eyeColorNode) {
		
		EyeColor eyeColor;
		switch (eyeColorNode.asText()) {
			case "blue":
				eyeColor = EyeColor.blue;
				break;
			case "brown":
				eyeColor = EyeColor.brown;
				break;
			default:
				eyeColor = EyeColor.green;
		}
		return eyeColor;
	}
	
	private Gender parseGender(JsonNode genderNode) {

		Gender gender;
		switch (genderNode.asText()) {
			case "male":
				gender = Gender.male;
				break;
			default:
				gender = Gender.female;
		}
		return gender;
	}
	
	
	private List<CharSequence> parseTags(JsonNode tagsNode) {

		List<CharSequence> tags = new ArrayList<>();
		for (JsonNode tagNode : tagsNode) {
			tags.add(tagNode.asText());
		}
		return tags;
	}
	
	
	private List<Friend> parseFriends(JsonNode friendsNode) {

		List<Friend> friends = new ArrayList<>();
		for (JsonNode friendNode : friendsNode) {
			Friend friend = Friend.newBuilder()
					.setId(friendsNode.path("id").asInt())
					.setName(friendNode.path("name").asText())
					.build();
			
			friends.add(friend);
		}
		return friends;
	}
	
	
	private Fruit parseFruits(JsonNode fruitNode) {

		Fruit fruit;
		switch (fruitNode.asText()) {
			case "apple":
				fruit = Fruit.apple;
				break;
			case "banana":
				fruit = Fruit.banana;
				break;
			default:
				fruit = Fruit.strawberry;
		}
		return fruit;
	}
}
