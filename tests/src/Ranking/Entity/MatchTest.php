<?php
namespace Ranking\Entity;

use \StdClass;
use \DateTime;
use \ReflectionClass;
use InvalidArgumentException as Argument;
use Ranking\Entity\Match;
use Ranking\Entity\User;
use Ranking\Entity\Map;
use Ranking\Entity\Team;

class MatchTest extends \PHPUnit_Framework_TestCase
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
     * @covers Ranking\Entity\Match::__construct
     */
    public function testConstruct()
    {
        $match = new Match();
        $this->assertAttributeInstanceOf('Doctrine\Common\Collections\ArrayCollection', 'teams', $match);
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
     * @covers Ranking\Entity\Match::setCreator
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
     * @covers Ranking\Entity\Match::setCreated
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
     * @covers Ranking\Entity\Match::setPlayed
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

    /**
     * @covers Ranking\Entity\Match::getTeams
     */
    public function testGetTeams()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->match->getTeams());
    }

    public function _validTeam()
    {
        $team = new Team;
        $user = new User;
        $user->setName('testbot');
        $team->setNumber(1)->setPlayer($user)->setRace('Arm');
        return array(
            array($team)
        );
    }

    /**
     * @dataProvider _validTeam
     * @covers Ranking\Entity\Match::getTeamValidator
     */
    public function testGetTeamValidator($team)
    {
        try {
            Match::getTeamValidator()->assert($team);
        } catch (Argument $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    /**
     * @depends testGetTeamValidator
     * @dataProvider _validTeam
     * @covers Ranking\Entity\Match::addTeam
     */
    public function testAddTeamWithValidArgument($team)
    {
        $instance = $this->match->addTeam($team);
        $this->assertEquals($this->match, $instance, 'Fluent interface failed');
        $this->assertAttributeContains($team, 'teams', $this->match, 'Team not found in ArrayCollection');
        return array($this->match, $team);
    }

    /**
     * @dataProvider _validTeam
     * @depends testGetTeamValidator
     * @expectedException InvalidArgumentException
     * @covers Ranking\Entity\Match::addTeam
     */
    public function testAddTeamWithARepeatedValue($team)
    {
        $this->match->addTeam($team)->addTeam($team);
    }


}