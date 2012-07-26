<?php
use Ranking\Entity\Match;
use Ranking\Entity\User;
use Ranking\Entity\Map;

class MatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Ranking\Entity\Match::getId
     */
    public function testGetId()
    {
        $id       = 1;
        $match    = new Match();
        $class    = new ReflectionClass($match);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($match, $id);

        $this->assertEquals($id, $match->getId());
    }

    public function _propertyAndValidValue() 
    {
        $user = new User();
        $user->setName('testbot');
        $map  = new Map();
        $map->setName('Test Dome');

        return array(
            array('created', new DateTime),
            array('played', new DateTime),
            array('creator', $user),
            array('map', $map)
        );
    }

    /**
     * @dataProvider _propertyAndValidValue
     */
    public function testGetters($propertyName, $propertyValue)
    {
        $match    = new Match();
        $class    = new ReflectionClass($match);
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($match, $propertyValue);

        $methodName = 'get'.ucfirst(strtolower($propertyName));
        $msgBase    = 'Method "%s" was expected to return "%s"';
        if ($propertyValue instanceof DateTime) {
            $propertyValueString = $propertyValue->format('Y-m-d');
        } else {
            $propertyValueString = (string) $propertyValue;
        }
        $msg        = sprintf($msgBase, $methodName, $propertyValueString);
        $this->assertEquals($propertyValue, $match->$methodName(), $msg);
    }
}