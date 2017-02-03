var PersonPb = require('./person_pb');
var PersonCollectionPb = require('./person_collection_pb');
var FriendPb = require('./friend_pb');


exports.convert = function (testData) {
	
	var persons = [];
	testData.persons.forEach((personNode) => {
		persons.push(parsePerson(personNode));
	});	
	
	var personCollection = new PersonCollectionPb.PersonCollection();
	personCollection.setPersonsList(persons);
	return personCollection;
}

var parsePerson = function(personNode) {
	person = new PersonPb.Person();
	person.setId(personNode.id)
	person.setIndex(personNode.index)
	person.setGuid(personNode.guid)
	person.setIsactive(personNode.isActive)
	person.setBalance(personNode.balance)
	person.setPicture(personNode.picture)
	person.setAge(personNode.age)
	person.setEyecolor(parseEyeColor(personNode.eyeColor))
	person.setName(personNode.name)
	person.setGender(parseGender(personNode.gender))
	person.setCompany(personNode.company)
	person.setEmail(personNode.email)
	person.setPhone(personNode.phone)
	person.setAddress(personNode.address)
	person.setAbout(personNode.about)
	person.setRegistered(personNode.registered)
	person.setLatitude(personNode.latitude)
	person.setLongitude(personNode.longitude)
	person.setTagsList(parseTags(personNode.tags))
	person.setFriendsList(parseFriends(personNode.friends))
	person.setGreeting(personNode.greeting)
	person.setFavoritefruit(parseFruits(personNode.favoriteFruit))
	
	return person;
}

var parseEyeColor = function(eyeColorNode) {
	
	var eyeColor;
	switch (eyeColorNode) {
		case "blue":
			eyeColor = proto.Person.EyeColor.BLUE;
			break;
		case "brown":
			eyeColor = proto.Person.EyeColor.BROWN;
			break;
		default:
			eyeColor = proto.Person.EyeColor.GREEN;
	}
	return eyeColor;
}

var parseGender = function(genderNode) {

	var gender;
	switch (genderNode) {
		case "male":
			gender = proto.Person.Gender.MALE;
			break;
		default:
			gender = proto.Person.Gender.FEMALE;
	}
	return gender;
}

var parseTags = function(tagsNode) {

	var tags = [];
	tagsNode.forEach((tagNode) => {
		tags.push(tagNode);
	});
	return tags;
}


var parseFriends = function(friendsNode) {

	var friends = [];
	friendsNode.forEach((friendNode) => {
		friend = new FriendPb.Friend();
		friend.setId(friendsNode.id)
		friend.setName(friendNode.name)
		friends.push(friend);
	});
	return friends;
}


var parseFruits = function(fruitNode) {

	var fruit;
	switch (fruitNode) {
		case "apple":
			fruit = proto.Person.Fruit.APPLE;
			break;
		case "banana":
			fruit = proto.Person.Fruit.BANANA;
			break;
		default:
			fruit = proto.Person.Fruit.STRAWBERRY;
	}
	return fruit;
}











