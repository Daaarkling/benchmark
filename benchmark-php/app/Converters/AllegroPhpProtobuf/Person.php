<?php
/**
 * Auto generated from person.proto at 2016-12-11 15:45:21
 *
 * Benchmark.Converters.AllegroPhpProtobuf package
 */

namespace Benchmark\Converters\AllegroPhpProtobuf {
/**
 * Person message
 */
class Person extends \ProtobufMessage
{
    /* Field index constants */
    const _ID = 1;
    const INDEX = 2;
    const GUID = 3;
    const ISACTIVE = 4;
    const BALANCE = 5;
    const PICTURE = 6;
    const AGE = 7;
    const EYECOLOR = 8;
    const NAME = 9;
    const GENDER = 10;
    const COMPANY = 11;
    const EMAIL = 12;
    const PHONE = 13;
    const ADDRESS = 14;
    const ABOUT = 15;
    const REGISTERED = 16;
    const LATITUDE = 17;
    const LONGITUDE = 18;
    const TAGS = 19;
    const FRIENDS = 20;
    const GREETING = 21;
    const FAVORITEFRUIT = 22;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::_ID => array(
            'name' => '_id',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::INDEX => array(
            'name' => 'index',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::GUID => array(
            'name' => 'guid',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::ISACTIVE => array(
            'name' => 'isActive',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_BOOL,
        ),
        self::BALANCE => array(
            'name' => 'balance',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::PICTURE => array(
            'name' => 'picture',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::AGE => array(
            'name' => 'age',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_INT,
        ),
        self::EYECOLOR => array(
            'name' => 'eyeColor',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::NAME => array(
            'name' => 'name',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::GENDER => array(
            'name' => 'gender',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::COMPANY => array(
            'name' => 'company',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::EMAIL => array(
            'name' => 'email',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::PHONE => array(
            'name' => 'phone',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::ADDRESS => array(
            'name' => 'address',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::ABOUT => array(
            'name' => 'about',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::REGISTERED => array(
            'name' => 'registered',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::LATITUDE => array(
            'name' => 'latitude',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_FLOAT,
        ),
        self::LONGITUDE => array(
            'name' => 'longitude',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_FLOAT,
        ),
        self::TAGS => array(
            'name' => 'tags',
            'repeated' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::FRIENDS => array(
            'name' => 'friends',
            'repeated' => true,
            'type' => '\Benchmark\Converters\AllegroPhpProtobuf\Friend'
        ),
        self::GREETING => array(
            'name' => 'greeting',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
        self::FAVORITEFRUIT => array(
            'name' => 'favoriteFruit',
            'required' => true,
            'type' => \ProtobufMessage::PB_TYPE_STRING,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::_ID] = null;
        $this->values[self::INDEX] = null;
        $this->values[self::GUID] = null;
        $this->values[self::ISACTIVE] = null;
        $this->values[self::BALANCE] = null;
        $this->values[self::PICTURE] = null;
        $this->values[self::AGE] = null;
        $this->values[self::EYECOLOR] = null;
        $this->values[self::NAME] = null;
        $this->values[self::GENDER] = null;
        $this->values[self::COMPANY] = null;
        $this->values[self::EMAIL] = null;
        $this->values[self::PHONE] = null;
        $this->values[self::ADDRESS] = null;
        $this->values[self::ABOUT] = null;
        $this->values[self::REGISTERED] = null;
        $this->values[self::LATITUDE] = null;
        $this->values[self::LONGITUDE] = null;
        $this->values[self::TAGS] = array();
        $this->values[self::FRIENDS] = array();
        $this->values[self::GREETING] = null;
        $this->values[self::FAVORITEFRUIT] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of '_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::_ID, $value);
    }

    /**
     * Returns value of '_id' property
     *
     * @return string
     */
    public function getId()
    {
        $value = $this->get(self::_ID);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'index' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setIndex($value)
    {
        return $this->set(self::INDEX, $value);
    }

    /**
     * Returns value of 'index' property
     *
     * @return integer
     */
    public function getIndex()
    {
        $value = $this->get(self::INDEX);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'guid' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setGuid($value)
    {
        return $this->set(self::GUID, $value);
    }

    /**
     * Returns value of 'guid' property
     *
     * @return string
     */
    public function getGuid()
    {
        $value = $this->get(self::GUID);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'isActive' property
     *
     * @param boolean $value Property value
     *
     * @return null
     */
    public function setIsActive($value)
    {
        return $this->set(self::ISACTIVE, $value);
    }

    /**
     * Returns value of 'isActive' property
     *
     * @return boolean
     */
    public function getIsActive()
    {
        $value = $this->get(self::ISACTIVE);
        return $value === null ? (boolean)$value : $value;
    }

    /**
     * Sets value of 'balance' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setBalance($value)
    {
        return $this->set(self::BALANCE, $value);
    }

    /**
     * Returns value of 'balance' property
     *
     * @return string
     */
    public function getBalance()
    {
        $value = $this->get(self::BALANCE);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'picture' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPicture($value)
    {
        return $this->set(self::PICTURE, $value);
    }

    /**
     * Returns value of 'picture' property
     *
     * @return string
     */
    public function getPicture()
    {
        $value = $this->get(self::PICTURE);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'age' property
     *
     * @param integer $value Property value
     *
     * @return null
     */
    public function setAge($value)
    {
        return $this->set(self::AGE, $value);
    }

    /**
     * Returns value of 'age' property
     *
     * @return integer
     */
    public function getAge()
    {
        $value = $this->get(self::AGE);
        return $value === null ? (integer)$value : $value;
    }

    /**
     * Sets value of 'eyeColor' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEyeColor($value)
    {
        return $this->set(self::EYECOLOR, $value);
    }

    /**
     * Returns value of 'eyeColor' property
     *
     * @return string
     */
    public function getEyeColor()
    {
        $value = $this->get(self::EYECOLOR);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'name' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setName($value)
    {
        return $this->set(self::NAME, $value);
    }

    /**
     * Returns value of 'name' property
     *
     * @return string
     */
    public function getName()
    {
        $value = $this->get(self::NAME);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'gender' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setGender($value)
    {
        return $this->set(self::GENDER, $value);
    }

    /**
     * Returns value of 'gender' property
     *
     * @return string
     */
    public function getGender()
    {
        $value = $this->get(self::GENDER);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'company' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setCompany($value)
    {
        return $this->set(self::COMPANY, $value);
    }

    /**
     * Returns value of 'company' property
     *
     * @return string
     */
    public function getCompany()
    {
        $value = $this->get(self::COMPANY);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'email' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setEmail($value)
    {
        return $this->set(self::EMAIL, $value);
    }

    /**
     * Returns value of 'email' property
     *
     * @return string
     */
    public function getEmail()
    {
        $value = $this->get(self::EMAIL);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'phone' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setPhone($value)
    {
        return $this->set(self::PHONE, $value);
    }

    /**
     * Returns value of 'phone' property
     *
     * @return string
     */
    public function getPhone()
    {
        $value = $this->get(self::PHONE);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'address' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAddress($value)
    {
        return $this->set(self::ADDRESS, $value);
    }

    /**
     * Returns value of 'address' property
     *
     * @return string
     */
    public function getAddress()
    {
        $value = $this->get(self::ADDRESS);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'about' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setAbout($value)
    {
        return $this->set(self::ABOUT, $value);
    }

    /**
     * Returns value of 'about' property
     *
     * @return string
     */
    public function getAbout()
    {
        $value = $this->get(self::ABOUT);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'registered' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setRegistered($value)
    {
        return $this->set(self::REGISTERED, $value);
    }

    /**
     * Returns value of 'registered' property
     *
     * @return string
     */
    public function getRegistered()
    {
        $value = $this->get(self::REGISTERED);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'latitude' property
     *
     * @param double $value Property value
     *
     * @return null
     */
    public function setLatitude($value)
    {
        return $this->set(self::LATITUDE, $value);
    }

    /**
     * Returns value of 'latitude' property
     *
     * @return double
     */
    public function getLatitude()
    {
        $value = $this->get(self::LATITUDE);
        return $value === null ? (double)$value : $value;
    }

    /**
     * Sets value of 'longitude' property
     *
     * @param double $value Property value
     *
     * @return null
     */
    public function setLongitude($value)
    {
        return $this->set(self::LONGITUDE, $value);
    }

    /**
     * Returns value of 'longitude' property
     *
     * @return double
     */
    public function getLongitude()
    {
        $value = $this->get(self::LONGITUDE);
        return $value === null ? (double)$value : $value;
    }

    /**
     * Appends value to 'tags' list
     *
     * @param string $value Value to append
     *
     * @return null
     */
    public function appendTags($value)
    {
        return $this->append(self::TAGS, $value);
    }

    /**
     * Clears 'tags' list
     *
     * @return null
     */
    public function clearTags()
    {
        return $this->clear(self::TAGS);
    }

    /**
     * Returns 'tags' list
     *
     * @return string[]
     */
    public function getTags()
    {
        return $this->get(self::TAGS);
    }

    /**
     * Returns 'tags' iterator
     *
     * @return \ArrayIterator
     */
    public function getTagsIterator()
    {
        return new \ArrayIterator($this->get(self::TAGS));
    }

    /**
     * Returns element from 'tags' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return string
     */
    public function getTagsAt($offset)
    {
        return $this->get(self::TAGS, $offset);
    }

    /**
     * Returns count of 'tags' list
     *
     * @return int
     */
    public function getTagsCount()
    {
        return $this->count(self::TAGS);
    }

    /**
     * Appends value to 'friends' list
     *
     * @param \Benchmark\Converters\AllegroPhpProtobuf\Friend $value Value to append
     *
     * @return null
     */
    public function appendFriends(\Benchmark\Converters\AllegroPhpProtobuf\Friend $value)
    {
        return $this->append(self::FRIENDS, $value);
    }

    /**
     * Clears 'friends' list
     *
     * @return null
     */
    public function clearFriends()
    {
        return $this->clear(self::FRIENDS);
    }

    /**
     * Returns 'friends' list
     *
     * @return \Benchmark\Converters\AllegroPhpProtobuf\Friend[]
     */
    public function getFriends()
    {
        return $this->get(self::FRIENDS);
    }

    /**
     * Returns 'friends' iterator
     *
     * @return \ArrayIterator
     */
    public function getFriendsIterator()
    {
        return new \ArrayIterator($this->get(self::FRIENDS));
    }

    /**
     * Returns element from 'friends' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Benchmark\Converters\AllegroPhpProtobuf\Friend
     */
    public function getFriendsAt($offset)
    {
        return $this->get(self::FRIENDS, $offset);
    }

    /**
     * Returns count of 'friends' list
     *
     * @return int
     */
    public function getFriendsCount()
    {
        return $this->count(self::FRIENDS);
    }

    /**
     * Sets value of 'greeting' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setGreeting($value)
    {
        return $this->set(self::GREETING, $value);
    }

    /**
     * Returns value of 'greeting' property
     *
     * @return string
     */
    public function getGreeting()
    {
        $value = $this->get(self::GREETING);
        return $value === null ? (string)$value : $value;
    }

    /**
     * Sets value of 'favoriteFruit' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setFavoriteFruit($value)
    {
        return $this->set(self::FAVORITEFRUIT, $value);
    }

    /**
     * Returns value of 'favoriteFruit' property
     *
     * @return string
     */
    public function getFavoriteFruit()
    {
        $value = $this->get(self::FAVORITEFRUIT);
        return $value === null ? (string)$value : $value;
    }
}
}