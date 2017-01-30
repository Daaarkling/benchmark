<?php


namespace Benchmark\Converters\AllegroPhpProtobuf;


use Benchmark\Converters\IDataConverter;

class ProtobufConverter implements IDataConverter
{


	public function convertData($arrayData)
	{
		$personCollection = new PersonCollection();
		
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

			foreach ($personData['tags'] as $tag) {
				$person->appendTags($tag);
			};


			if ($personData['friends']) {
				foreach ($personData['friends'] as $friendData) {
					$friend = new Friend();
					$friend->setId($friendData['id']);
					$friend->setName($friendData['name']);
					$person->appendFriends($friend);
				}
			}
			$person->setGreeting($personData['greeting']);
			$person->setFavoriteFruit($personData['favoriteFruit']);

			$personCollection->appendPersons($person);
		}

		return $personCollection;
	}


}
