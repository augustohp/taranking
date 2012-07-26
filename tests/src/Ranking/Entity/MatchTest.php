<?php
use Ranking\Entity\Match;
use Ranking\Entity\User;
use Ranking\Entity\Map;

class MatchTest extends PHPUnit_Framework_TestCase
{
    protected $match, $user, $map;

    public function setUp()
    {
        $this->match = new Match();
        $this->user  = new User();
        $this->map   = new Map();
        $this->user->setName('testbot');
        $this->map->setName('Test Dome');
    }

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

    /**
     * @covers Ranking\Entity\Match::setCreator
     */
    public function testSetCreator()
    {
        $instance = $this->match->setCreator($this->user);
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeEquals($this->user, 'creator', $this->match);
        return array($this->match, $this->user);
    }

    /**
     * @depends testSetCreator
     * @covers Ranking\Entity\Match::getCreator
     */
    public function testGetCreator($args)
    {
        list($match, $creator) = $args;
        $this->assertEquals($creator, $match->getCreator());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetCreatorWithInvalidArgument()
    {
        $value = new StdClass;
        $this->match->setCreator($value);
    }

    /**
     * @covers Ranking\Entity\Match::setCreated
     */
    public function testSetCreatedWithoutAnyArgument()
    {
        $instance = $this->match->setCreated();
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeInstanceOf('DateTime', 'created', $this->match);
    }

    /**
     * @covers Ranking\Entity\Match::setCreated
     */
    public function testSetCreatedWithDateTimeArgument()
    {
        $now = new DateTime();
        $instance = $this->match->setCreated($now);
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeEquals($now, 'created', $this->match);
        return array($this->match, $now);
    }

    /**
     * @depends testSetCreatedWithDateTimeArgument
     * @covers Ranking\Entity\Match::getCreated
     */
    public function testGetCreated($args)
    {
        list($match, $when) = $args;
        $this->assertEquals($when, $match->getCreated());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetCreatedWithInvalidArgument()
    {
        $value = new StdClass;
        $this->match->setCreated($value);
    }

    /**
     * @covers Ranking\Entity\Match::setPlayed
     */
    public function testSetPlayedWithoutAnyArgument()
    {
        $instance = $this->match->setPlayed();
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeInstanceOf('DateTime', 'played', $this->match);
    }

    /**
     * @covers Ranking\Entity\Match::setPlayed
     */
    public function testSetPlayedWithDateTimeArgument()
    {
        $now = new DateTime();
        $instance = $this->match->setPlayed($now);
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeEquals($now, 'played', $this->match);
        return array($this->match, $now);
    }

    /**
     * @depends testSetPlayedWithDateTimeArgument
     * @covers Ranking\Entity\Match::getPlayed
     */
    public function testGetPlayed($args)
    {
        list($match, $when) = $args;
        $this->assertEquals($when, $match->getPlayed());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetPlayedWithInvalidArgument()
    {
        $value = new StdClass;
        $this->match->setPlayed($value);
    }

    /**
     * @covers Ranking\Entity\Match::setMap
     */
    public function testSetMap()
    {
        $instance = $this->match->setMap($this->map);
        $this->assertEquals($this->match, $instance);
        $this->assertAttributeEquals($this->map, 'map', $this->match);
        return array($this->match, $this->map);
    }

    /**
     * @depends testSetMap
     * @covers Ranking\Entity\Match::getMap
     */
    public function testGetMap($args)
    {
        list($match, $map) = $args;
        $this->assertEquals($map, $match->getMap());
    }
}