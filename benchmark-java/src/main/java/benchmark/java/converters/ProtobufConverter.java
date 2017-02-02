package benchmark.java.converters;

import com.fasterxml.jackson.databind.JsonNode;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import benchmark.java.metrics.protobuf.FriendOuterClass;
import benchmark.java.metrics.protobuf.PersonCollectionOuterClass;
import benchmark.java.metrics.protobuf.PersonOuterClass;
import benchmark.java.utils.JsonLoader;


public class ProtobufConverter implements IDataConverter {
	
	/**
	 * 
	 * @param testDataFile
	 * @return PersonCollectionOuterClass object
	 */
	@Override
	public Object convertData(File testDataFile) {
		
		try {
			JsonNode testData = JsonLoader.loadResource(testDataFile);
			
			List<PersonOuterClass.Person> persons = new ArrayList<>();
			
			for (JsonNode personNode : testData.path("persons")) {
				persons.add(parsePerson(personNode));
			}
			
			PersonCollectionOuterClass.PersonCollection personCollection = PersonCollectionOuterClass.PersonCollection.newBuilder()
					.addAllPersons(persons)
					.build();
			
			return personCollection;
			
		} catch (IOException ex) {
			Logger.getLogger(ProtobufConverter.class.getName()).log(Level.SEVERE, null, ex);
			return null;
		}
		
	}
	
	private PersonOuterClass.Person parsePerson(JsonNode personNode) {
		
		PersonOuterClass.Person person = PersonOuterClass.Person.newBuilder()
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
				.addAllTags(parseTags(personNode.path("tags")))
				.addAllFriends(parseFriends(personNode.path("friends")))
				.setGreeting(personNode.path("greeting").asText())
				.setFavoriteFruit(parseFruits(personNode.path("favoriteFruit")))
				.build();
		
		return person;
	}
	
	
	private PersonOuterClass.Person.EyeColor parseEyeColor(JsonNode eyeColorNode) {
		
		PersonOuterClass.Person.EyeColor eyeColor;
		switch (eyeColorNode.asText()) {
			case "blue":
				eyeColor = PersonOuterClass.Person.EyeColor.BLUE;
				break;
			case "brown":
				eyeColor = PersonOuterClass.Person.EyeColor.BROWN;
				break;
			default:
				eyeColor = PersonOuterClass.Person.EyeColor.GREEN;
		}
		return eyeColor;
	}
	
	private PersonOuterClass.Person.Gender parseGender(JsonNode genderNode) {

		PersonOuterClass.Person.Gender gender;
		switch (genderNode.asText()) {
			case "male":
				gender = PersonOuterClass.Person.Gender.MALE;
				break;
			default:
				gender = PersonOuterClass.Person.Gender.FEMALE;
		}
		return gender;
	}
	
	
	private List<String> parseTags(JsonNode tagsNode) {

		List<String> tags = new ArrayList<>();
		for (JsonNode tagNode : tagsNode) {
			tags.add(tagNode.asText());
		}
		return tags;
	}
	
	
	private List<FriendOuterClass.Friend> parseFriends(JsonNode friendsNode) {

		List<FriendOuterClass.Friend> friends = new ArrayList<>();
		for (JsonNode friendNode : friendsNode) {
			FriendOuterClass.Friend friend = FriendOuterClass.Friend.newBuilder()
					.setId(friendsNode.path("id").asInt())
					.setName(friendNode.path("name").asText())
					.build();
			
			friends.add(friend);
		}
		return friends;
	}
	
	
	private PersonOuterClass.Person.Fruit parseFruits(JsonNode fruitNode) {

		PersonOuterClass.Person.Fruit fruit;
		switch (fruitNode.asText()) {
			case "apple":
				fruit = PersonOuterClass.Person.Fruit.APPLE;
				break;
			case "banana":
				fruit = PersonOuterClass.Person.Fruit.BANANA;
				break;
			default:
				fruit = PersonOuterClass.Person.Fruit.STRAWBERRY;
		}
		return fruit;
	}
}
