<?php
/**
 * Auto generated from person_collection.proto at 2016-12-11 15:45:21
 *
 * Benchmark.Converters.AllegroPhpProtobuf package
 */

namespace Benchmark\Converters\AllegroPhpProtobuf {
/**
 * PersonCollection message
 */
class PersonCollection extends \ProtobufMessage
{
    /* Field index constants */
    const PERSONS = 1;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::PERSONS => array(
            'name' => 'persons',
            'repeated' => true,
            'type' => '\Benchmark\Converters\AllegroPhpProtobuf\Person'
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
        $this->values[self::PERSONS] = array();
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
     * Appends value to 'persons' list
     *
     * @param \Benchmark\Converters\AllegroPhpProtobuf\Person $value Value to append
     *
     * @return null
     */
    public function appendPersons(\Benchmark\Converters\AllegroPhpProtobuf\Person $value)
    {
        return $this->append(self::PERSONS, $value);
    }

    /**
     * Clears 'persons' list
     *
     * @return null
     */
    public function clearPersons()
    {
        return $this->clear(self::PERSONS);
    }

    /**
     * Returns 'persons' list
     *
     * @return \Benchmark\Converters\AllegroPhpProtobuf\Person[]
     */
    public function getPersons()
    {
        return $this->get(self::PERSONS);
    }

    /**
     * Returns 'persons' iterator
     *
     * @return \ArrayIterator
     */
    public function getPersonsIterator()
    {
        return new \ArrayIterator($this->get(self::PERSONS));
    }

    /**
     * Returns element from 'persons' list at given offset
     *
     * @param int $offset Position in list
     *
     * @return \Benchmark\Converters\AllegroPhpProtobuf\Person
     */
    public function getPersonsAt($offset)
    {
        return $this->get(self::PERSONS, $offset);
    }

    /**
     * Returns count of 'persons' list
     *
     * @return int
     */
    public function getPersonsCount()
    {
        return $this->count(self::PERSONS);
    }
}
}