package benchmark.java.metrics.json;

import com.google.gson.Gson;
import com.google.gson.stream.JsonReader;
import com.google.gson.stream.JsonWriter;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import benchmark.java.Config;
import benchmark.java.entities.Friend;
import benchmark.java.entities.Person;
import benchmark.java.entities.PersonCollection;
import benchmark.java.metrics.AMetric;
import benchmark.java.metrics.Info;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonArray;
import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;
import com.google.gson.JsonSerializationContext;
import com.google.gson.JsonSerializer;
import java.lang.reflect.Type;


public class GsonCustomMetric extends AMetric implements JsonSerializer<PersonCollection>, JsonDeserializer<PersonCollection> {
	
	private Gson gson;
	private final Info info = new Info(Config.Format.JSON, "Gson Custom", "https://github.com/google/gson", "2.8.0");


	
	@Override
	public Info getInfo() {
		return info;
	}
	
	
	@Override
	protected void prepareBenchmark() {
		gson = new GsonBuilder()
				.registerTypeAdapter(PersonCollection.class, this)
				.registerTypeAdapter(PersonCollection.class, this)
				.create();
	}

	
	@Override
	protected boolean serialize(Object data, OutputStream output) throws Exception {

		JsonWriter writer =  new JsonWriter(new OutputStreamWriter(output));
		gson.toJson(data, PersonCollection.class, writer);
		writer.close();
		return true;
	}

	
	@Override
	protected Object deserialize(InputStream input, byte[] bytes) throws Exception {
		
		JsonReader reader = new JsonReader(new InputStreamReader(input));
		PersonCollection pC = gson.fromJson(reader, PersonCollection.class);
		reader.close();
		return pC;		
	}

	
	@Override
	public JsonElement serialize(PersonCollection src, Type typeOfSrc, JsonSerializationContext context) {
		
		JsonObject personCollectionObj = new JsonObject();
		JsonArray personCollectionArray = new JsonArray();
		personCollectionObj.add("persons", personCollectionArray);
		
		for (Person person : src.getPersons()) {
			JsonObject personObj = new JsonObject();
			personObj.addProperty("id", person.getId());
			personObj.addProperty("index", person.getIndex());
			personObj.addProperty("guid", person.getGuid());
			personObj.addProperty("isActive", person.isIsActive());
			personObj.addProperty("balance", person.getBalance());
			personObj.addProperty("picture", person.getPicture());
			personObj.addProperty("age", person.getAge());
			personObj.addProperty("eyeColor", person.getEyeColor());
			personObj.addProperty("name", person.getName());
			personObj.addProperty("gender", person.getGender());
			personObj.addProperty("company", person.getCompany());
			personObj.addProperty("email", person.getEmail());
			personObj.addProperty("phone", person.getPhone());
			personObj.addProperty("address", person.getAddress());
			personObj.addProperty("about", person.getAbout());
			personObj.addProperty("registered", person.getRegistered());
			personObj.addProperty("latitude", person.getLatitude());
			personObj.addProperty("longitude", person.getLongitude());
			
			JsonArray tagsArray = new JsonArray();
			for(String tag : person.getTags()) {
				tagsArray.add(tag);
			}
			personObj.add("tags", tagsArray);
			
			JsonArray friendsArray = new JsonArray();
			for (Friend friend : person.getFriends()) {
				JsonObject friendObj = new JsonObject();
				friendObj.addProperty("id", friend.getId());
				friendObj.addProperty("name", friend.getName());
				friendsArray.add(friendObj);
			}
			personObj.add("friends", friendsArray);
			
			personObj.addProperty("greeting", person.getGreeting());
			personObj.addProperty("favoriteFruit", person.getFavoriteFruit());
			
			personCollectionArray.add(personObj);
		}
		return personCollectionObj;
	}

	@Override
	public PersonCollection deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
		
		PersonCollection personCollection = new PersonCollection();
		
		JsonArray personCollectionArray = json.getAsJsonObject().get("persons").getAsJsonArray();
		for (JsonElement personEl : personCollectionArray) {
			JsonObject personObj = personEl.getAsJsonObject();
			Person person = new Person();
			person.setId(personObj.get("id").getAsString());
			person.setIndex(personObj.get("index").getAsInt());
			person.setGuid(personObj.get("guid").getAsString());
			person.setIsActive(personObj.get("isActive").getAsBoolean());
			person.setBalance(personObj.get("balance").getAsString());
			person.setPicture(personObj.get("picture").getAsString());
			person.setAge(personObj.get("age").getAsInt());
			person.setEyeColor(personObj.get("eyeColor").getAsString());
			person.setName(personObj.get("name").getAsString());
			person.setGender(personObj.get("gender").getAsString());
			person.setCompany(personObj.get("company").getAsString());
			person.setEmail(personObj.get("email").getAsString());
			person.setPhone(personObj.get("phone").getAsString());
			person.setAddress(personObj.get("address").getAsString());
			person.setAbout(personObj.get("about").getAsString());
			person.setRegistered(personObj.get("registered").getAsString());
			person.setLatitude(personObj.get("latitude").getAsFloat());
			person.setLongitude( personObj.get("longitude").getAsFloat());
			
			JsonArray tagsArray = personObj.get("tags").getAsJsonArray();
			for(JsonElement tagEl : tagsArray) {
				person.addTag(tagEl.getAsString());
			}
			
			JsonArray friendsArray = personObj.get("friends").getAsJsonArray();
			for (JsonElement friendEl : friendsArray) {
				JsonObject friendObj = friendEl.getAsJsonObject();
				Friend friend = new Friend();
				friend.setId(friendObj.get("id").getAsInt());
				friend.setName(friendObj.get("name").getAsString());
				person.addFriend(friend);
			}
			
			person.setGreeting(personObj.get("greeting").getAsString());
			person.setFavoriteFruit(personObj.get("favoriteFruit").getAsString());
			
			personCollection.addPerson(person);
		}
		
		
		return personCollection;
	}
	
	
	
	
}
