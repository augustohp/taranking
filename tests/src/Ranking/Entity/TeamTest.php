<?php
namespace Ranking\Entity;

use StdClass;
use \DateTime;
use \ReflectionClass;
use Ranking\Entity\Team;
use Ranking\Entity\User;
use Ranking\Entity\Match;

class TeamTest extends \PHPUnit_Framework_TestCase
{
    protected $team, $user, $match;

    public function setUp()
    {
        $this->team = new Team;
        $this->user = new User;
        $this->user->setName('testbot');
        $this->match = new Match;
    }

    /**
     * @covers Ranking\Entity\Team::getId
     */
    public function testGetId()
    {
        $id       = 1;
        $class    = new ReflectionClass($this->team);
        $property = $class->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($this->team, $id);
        $this->assertAttributeEquals($id, 'id', $this->team);
        $this->assertEquals($id, $this->team->getId());
    }

    /**
     * @covers Ranking\Entity\Team::setMatch
     */
    public function testSetMatchWithValidArgument()
    {
        $instance = $this->team->setMatch($this->match);
        $this->assertEquals($this->team, $instance);
        $this->assertAttributeEquals($this->match, 'match', $this->team);
        return array($this->team, $this->match);
    }

    /**
     * @covers Ranking\Entity\Team::setMatch
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetMatchWithInvalidArgument()
    {
        $value = new StdClass;
        $this->team->setMatch($value);
    }

    /**
     * @depends testSetMatchWithValidArgument
     * @covers Ranking\Entity\Team::getMatch
     */
    public function testGetMatch($args)
    {
        list($team, $match) = $args;
        $this->assertEquals($match, $team->getMatch());
    }

    /**
     * @covers Ranking\Entity\Team::setPlayer
     */
    public function testSetPlayerWithValidArgument()
    {
        $instance = $this->team->setPlayer($this->user);
        $this->assertEquals($this->team, $instance);
        $this->assertAttributeEquals($this->user, 'player', $this->team);
        return array($this->team, $this->user);
    }

    /**
     * @covers Ranking\Entity\Team::setPlayer
     * @expectedException PHPUnit_Framework_Error
     */
    public function testSetPlayerWithInvalidArgument()
    {
        $player = new StdClass;
        $this->team->setPlayer($player);
    }

    /**
     * @depends testSetPlayerWithValidArgument
     * @covers Ranking\Entity\Team::getPlayer
     */
    public function testGetPlayer($args)
    {
        list($team, $player) = $args;
        $this->assertEquals($player, $team->getPlayer());
    }

    public function _validRace()
    {
        return array(
            array('Arm'),
            array('Core'),
            array('arm'),
            array('core'),
            array('ARM'),
            array('CORE')
        );
    }

    /**
     * @dataProvider _validRace
     * @covers Ranking\Entity\Team::filterRace
     */
    public function testFilterRace($race)
    {
        $expect = ucfirst(strtolower($race));
        $this->assertEquals($expect, Team::filterRace($race));
    }

    /**
     * @depends testFilterRace
     * @dataProvider _validRace
     * @covers Ranking\Entity\Team::getRaceValidator
     */
    public function testGetRaceValidator($race)
    {
        try {
            $race = Team::filterRace($race);
            Team::getRaceValidator()->assert($race);
        } catch (Argument $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    /**
     * @depends testGetRaceValidator
     * @covers Ranking\Entity\Team::setRace
     * @dataProvider _validRace
     */
    public function testSetRaceWithValidArgument($race)
    {
        $race     = Team::filterRace($race);
        $instance = $this->team->setRace($race);
        $this->assertEquals($this->team, $instance);
        $this->assertAttributeEquals($race, 'race', $this->team);
    }

    /**
     * @depends testSetRaceWithValidArgument
     * @covers Ranking\Entity\Team::getRace
     */
    public function testGetRace()
    {
        $races = $this->_validRace();
        $races = array_shift($races);
        $race  = array_shift($races);
        $this->team->setRace($race);
        $this->assertEquals($race, $this->team->getRace());
    }

    public function _invalidRace()
    {
        return array(
            array('Human'),
            array('Elf'),
            array('Orc'),
            array(null),
            array(0)
        );
    }

    /**
     * @dataProvider _invalidRace
     * @covers Ranking\Entity\Team::setRace
     * @expectedException InvalidArgumentException
     */
    public function testSetRaceWithInvalidArgument($race)
    {
        $this->team->setRace($race);
    }

    /**
     * @dataProvider _validNumber
     * @covers Ranking\Entity\Team::getNumberValidator
     */
    public function testGetNumberValidator($number)
    {
        try {
            Team::getNumberValidator()->assert($number);
        } catch (Argument $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    public function _validNumber()
    {
        return array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(5),
            array(6),
            array(7),
            array(8),
            array(9),
            array(10)
        );
    }

    /**
     * @depends testGetNumberValidator
     * @dataProvider _validNumber
     * @covers Ranking\Entity\Team::setNumber
     */
    public function testSetNumberWithValidArgument($number)
    {
        $instance = $this->team->setNumber($number);
        $this->assertEquals($this->team, $instance);
        $this->assertAttributeEquals($number, 'number', $this->team);
    }

    public function _invalidNumber()
    {
        return array(
            array(null),
            array(0),
            array(' '),
            array(11),
            array(1.1),
            array(0.9),
        );
    }

    /**
     * @dataProvider _invalidNumber
     * @covers Ranking\Entity\Team::setNumber
     * @expectedException InvalidArgumentException
     */
    public function testSetNumberWithInvalidArgument($number)
    {
        $this->team->setNumber($number);
    }

    /**
     * @depends testSetNumberWithValidArgument
     * @covers Ranking\Entity\Team::getNumber
     */
    public function testGetNumber()
    {
        $numbers = $this->_validNumber();
        $numbers = array_pop($numbers);
        $number  = array_pop($numbers);
        $this->team->setNumber($number);
        $this->assertEquals($number, $this->team->getNumber());
    }
}