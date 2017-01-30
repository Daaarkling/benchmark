<?php


namespace Benchmark\Converters\GoogleProtobuf;


use Benchmark\Converters\IDataConverter;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;

class ProtobufConverter implements IDataConverter
{


	public function convertData($arrayData)
	{
		$personCollection = new PersonCollection();
		$persons = new RepeatedField(GPBType::MESSAGE, Person::class);

		foreach ($arrayData['persons'] as $personData) {

			$person = new Person();
			$person->setId($personData['id']);
			$person->setIndex($personData['index']);
			$person->setGuid($personData['guid']);
			$person->setIsActive($personData['isActive']);
			$person->setBalance($personData['balance']);
			$person->setPicture($personData['picture']);
			$person->setAge($personData['age']);
			$person->setEyeColor($personData['eyeColor']);

			$person->setName($personData['name']);
			$person->setGender($personData['gender']);
			$person->setCompany($personData['company']);
			$person->setEmail($personData['email']);
			$person->setPhone($personData['phone']);
			$person->setAddress($personData['address']);
			$person->setAbout($personData['about']);
			$person->setRegistered($personData['registered']);
			$person->setLatitude($personData['latitude']);
			$person->setLongitude($personData['longitude']);

			$tags = new RepeatedField(GPBType::STRING);
			foreach ($personData['tags'] as $tag) {
				$tags[] = $tag;
			};
			$person->setTags($tags);

			if ($personData['friends']) {
				$friends = new RepeatedField(GPBType::MESSAGE, Friend::class);
				foreach ($personData['friends'] as $friendData) {
					$friend = new Friend();
					$friend->setId($friendData['id']);
					$friend->setName($friendData['name']);
					$friends[] = $friend;
				}
				$person->setFriends($friends);
			}
			$person->setGreeting($personData['greeting']);
			$person->setFavoriteFruit($personData['favoriteFruit']);

			$persons[] = $person;
		}

		$personCollection->setPersons($persons);
		//$data = $personCollection->encode();

		return $personCollection;
	}


}