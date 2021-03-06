<?php
use Ranking\Entity\Map;

class MapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Ranking\Entity\Map::getId
     */
    public function testGetId()
    {
        $id    = 1;
        $map   = new Map;
        $class = new ReflectionClass('Ranking\Entity\Map');
        $prop  = $class->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($map, $id);
        $this->assertEquals($id, $map->getId());
    }

    /**
     * @covers Ranking\Entity\Map::getName
     */
    public function testGetName()
    {
        $name  = 'Gods of War';
        $map   = new Map;
        $class = new ReflectionClass('Ranking\Entity\Map');
        $prop  = $class->getProperty('name');
        $prop->setAccessible(true);
        $prop->setValue($map, $name);
        $this->assertEquals($name, $map->getName());
    }

    public function _validName()
    {
        return array(
            array('Gods of War'),
            array('[TDC] Core Bunker'),
            array('[TDC] Warm Center'),
            array('Eastside Westide'),
            array('Long Lakes'),
            array('Slate Gordon'),
            array('Great Divide'),
            array('The Pass'),
            array('Metal Heck'),
            array('Assault on Suburbia')
        );
    }

    /**
     * @dataProvider _validName
     * @depends testGetName
     * @covers Ranking\Entity\Map::setName
     */
    public function testSetName($name)
    {
        $map    = new Map;
        $fluent = $map->setName($name);
        $this->assertEquals($name, $map->getName());
        $this->assertInstanceOf('Ranking\Entity\Map', $fluent);
    }

    /**
     * @covers Ranking\Entity\Map::setCreated
     */
    public function testSetCreated()
    {
        $date   = new DateTime();
        $map    = new Map();
        $fluent = $map->setCreated($date);
        $this->assertInstanceOf('Ranking\Entity\Map', $fluent);
        $this->assertAttributeEquals($date, 'created', $map);
        return array($date, $map);
    }

    /**
     * @depends testSetCreated
     * @covers Ranking\Entity\Map::getCreated
     */
    public function testGetCreated(array $args)
    {
        list($date, $map) = $args;
        $this->assertEquals($date, $map->getCreated());
    }

    /**
     * @depends testSetCreated
     * @covers Ranking\Entity\Map::setCreated
     */
    public function testSetCreatedWithoutArguments()
    {
        $map = new Map;
        $map->setCreated();
        $this->assertAttributeInstanceOf('DateTime', 'created', $map);
    }
}