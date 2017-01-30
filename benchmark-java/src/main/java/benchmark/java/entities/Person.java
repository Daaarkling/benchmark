
package benchmark.java.entities;

import com.bluelinelabs.logansquare.annotation.JsonObject;
import com.dslplatform.json.CompiledJson;
import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;
import javax.xml.bind.annotation.XmlRootElement;

@XmlRootElement
@CompiledJson
@JsonObject(fieldDetectionPolicy = JsonObject.FieldDetectionPolicy.NONPRIVATE_FIELDS_AND_ACCESSORS)
public class Person implements Serializable {
    private String _id;
    private int index;
    private String guid;
    private boolean isActive;
    private String balance;
    private String picture;
    private int age;
    private String eyeColor;
    private String name;
    private String gender;
    private String company;
    private String email;
    private String phone;
    private String address;
    private String about;
    private String registered;
    private float latitude;
    private float longitude;
    private List<String> tags = new ArrayList<>();
    private List<Friend> friends = new ArrayList<>();
    private String greeting;
    private String favoriteFruit;

	
	public void addFriend(Friend friend) {	
		friends.add(friend);
	}

	
	public void addTag(String tag) {	
		tags.add(tag);
	}

	public String getId() {
		return _id;
	}

	public void setId(String _id) {
		this._id = _id;
	}

	public int getIndex() {
		return index;
	}

	public void setIndex(int index) {
		this.index = index;
	}

	public String getGuid() {
		return guid;
	}

	public void setGuid(String guid) {
		this.guid = guid;
	}

	public boolean isIsActive() {
		return isActive;
	}

	public void setIsActive(boolean isActive) {
		this.isActive = isActive;
	}

	public String getBalance() {
		return balance;
	}

	public void setBalance(String balance) {
		this.balance = balance;
	}

	public String getPicture() {
		return picture;
	}

	public void setPicture(String picture) {
		this.picture = picture;
	}

	public int getAge() {
		return age;
	}

	public void setAge(int age) {
		this.age = age;
	}

	public String getEyeColor() {
		return eyeColor;
	}

	public void setEyeColor(String eyeColor) {
		this.eyeColor = eyeColor;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getGender() {
		return gender;
	}

	public void setGender(String gender) {
		this.gender = gender;
	}

	public String getCompany() {
		return company;
	}

	public void setCompany(String company) {
		this.company = company;
	}

	public String getEmail() {
		return email;
	}

	public void setEmail(String email) {
		this.email = email;
	}

	public String getPhone() {
		return phone;
	}

	public void setPhone(String phone) {
		this.phone = phone;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	public String getAbout() {
		return about;
	}

	public void setAbout(String about) {
		this.about = about;
	}

	public String getRegistered() {
		return registered;
	}

	public void setRegistered(String registered) {
		this.registered = registered;
	}

	public float getLatitude() {
		return latitude;
	}

	public void setLatitude(float latitude) {
		this.latitude = latitude;
	}

	public float getLongitude() {
		return longitude;
	}

	public void setLongitude(float longitude) {
		this.longitude = longitude;
	}

	public List<String> getTags() {
		return tags;
	}

	public void setTags(List<String> tags) {
		this.tags = tags;
	}

	public List<Friend> getFriends() {
		return friends;
	}

	public void setFriends(List<Friend> friends) {
		this.friends = friends;
	}

	public String getGreeting() {
		return greeting;
	}

	public void setGreeting(String greeting) {
		this.greeting = greeting;
	}

	public String getFavoriteFruit() {
		return favoriteFruit;
	}

	public void setFavoriteFruit(String favoriteFruit) {
		this.favoriteFruit = favoriteFruit;
	}

	
	
	
}