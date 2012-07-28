<?php
namespace Ranking\Domain\Transaction;

use \StdClass;
use \ReflectionClass;
use InvalidArgumentException as Argument;
use Respect\Validation\Validator as V;
use Ranking\Entity\User;
use Ranking\Domain\Transaction\UsersToTeams;

class UsersToTeamsTest extends \PHPUnit_Framework_TestCase
{

    protected $script, $user;

    public function setUp()
    {
        $this->script = new UsersToTeams;
        $this->user   = new User;
        $this->user->setName('testbot')->setId(1);
    }

    public function testToStringConvertion()
    {
        try {
            $prefix = 'Ranking\Domain\Transaction\UsersToTeams: ';
            $bool   = V::string()->startsWith($prefix)
                       ->setName('Object to string convertion')
                       ->assert((string) $this->script);
            $this->assertTrue($bool);
        } catch (Argument $e) {
            $this->fail('[Validation Exception] '.PHP_EOL.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    /**
     * @covers Ranking\Domain\Transaction\UsersToTeams::__construct
     */
    public function testConstructWithoutArguments()
    {
        $script = new UsersToTeams;
        $this->assertAttributeEquals(array(), 'teams', $script);
    }

    /**
     * @covers Ranking\Domain\Transaction\UsersToTeams::getNextTeamNumber
     */
    public function testGetNextTeamNumberWithoutTeams()
    {
        $this->assertEquals(1, $this->script->getNextTeamNumber());
    }

    /**
     * @covers Ranking\Domain\Transaction\UsersToTeams::getNextTeamNumber
     */
    public function testGetNextTeamNumberWithOneTeamAlreadyOnIt()
    {
        $teams = array(1);
        $class = new ReflectionClass($this->script);
        $property = $class->getProperty('teams');
        $property->setAccessible(true);
        $property->setValue($this->script, $teams);
        $this->assertEquals(2, $this->script->getNextTeamNumber());
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @covers Ranking\Domain\Transaction\UsersToTeams::getTeamFromUser
     */
    public function testGetTeamFromUserWithInvalidUser()
    {
        $invalid = new StdClass;
        $this->script->getTeamFromUser($invalid);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @covers Ranking\Domain\Transaction\UsersToTeams::getTeamFromUser
     */
    public function testGetTeamFromUserWithoutArgument()
    {
        $this->script->getTeamFromUser();
    }

    /**
     * @covers Ranking\Domain\Transaction\UsersToTeams::getTeamFromUser
     */
    public function testGetTeamFromUserWithAValidUser()
    {
        $team = $this->script->getTeamFromUser($this->user);
        $this->assertEquals(1, $team->getNumber(), 'Team number different than expected (1)');
        $this->assertEquals($this->user, $team->getPlayer(), 'Player (user) not set on team');
        $this->assertEquals('Arm', $team->getRace(), 'Default value for race used to be "Arm"');
        return $team;
    }

    /**
     * @covers Ranking\Domain\Transaction\UsersToTeams::getTeamFromUser
     */
    public function testGetTeamFromUserWithDifferentRace()
    {
        $race = 'Core';
        $team = $this->script->getTeamFromUser($this->user, $race);
        $this->assertEquals(1, $team->getNumber(), 'Team number different than expected (1)');
        $this->assertEquals($this->user, $team->getPlayer(), 'Player (user) not set on team');
        $this->assertEquals($race, $team->getRace(), 'Race value different from expected: '.$race);   
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @covers Ranking\Domain\Transaction\UsersToTeams::addUser
     */
    public function testAddUserWithoutArguments()
    {
        $this->script->addUser();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @covers Ranking\Domain\Transaction\UsersToTeams::addUser
     */
    public function testAddUserWithInvalidArgument()
    {
        $invalid = new StdClass;
        $this->script->addUser($invalid);
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Domain\Transaction\UsersToTeams::getUserValidator
     */
    public function testGetUserValidatorWithEmptyTeams()
    {
        UsersToTeams::getUserValidator()->assert($this->script);
    }

    /**
     * @depends testGetTeamFromUserWithAValidUser
     * @covers Ranking\Domain\Transaction\UsersToTeams::getUserValidator
     */
    public function testGetUserValidatorWithAnExistingUser($team)
    {
        $teams     = array($team);
        $user      = $team->getPlayer();
        $validator = UsersToTeams::getUserValidator($user);
        $class     = new ReflectionClass($this->script);
        $property  = $class->getProperty('teams');
        $property->setAccessible(true);
        $property->setValue($this->script, $teams);
        $this->assertFalse($validator->validate($this->script), 'Validator should return false since user is already on a team');
    }

    /**
     * @expectedException InvalidArgumentException
     * @depends testGetUserValidatorWithAnExistingUser
     * @covers Ranking\Domain\Transaction\UsersToTeams::addUser
     */
    public function testAddUserAlreadyInTeamValidator()
    {
        $this->script->addUser($this->user);
        $this->script->addUser($this->user);
    }

    /**
     * @depends testGetUserValidatorWithAnExistingUser
     * @covers Ranking\Domain\Transaction\UsersToTeams::addUser
     */
    public function testAddUserWithValidArgument()
    {
        try {
            $validator = UsersToTeams::getUserValidator($this->user);
            $this->script->addUser($this->user);
            $this->assertFalse($validator->validate($this->script), 'User should be in a team already');
        } catch (Argument $e) {
            $this->fail('[Validation Exception] '.PHP_EOL.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    public function _validRace()
    {
        return array(
            array('Arm'),
            array('Core')
        );
    }

    /**
     * @dataProvider _validRace
     * @depends testAddUserWithValidArgument
     * @covers Ranking\Domain\Transaction\UsersToTeams::addUser
     */
    public function testAddUserWithASpecifiedRace($race)
    {
        try {
            $validator = UsersToTeams::getUserValidator($this->user);
            $this->script->addUser($this->user, $race);
            $this->assertFalse($validator->validate($this->script), 'User should be in a team already');
            $playerValidator = V::instance('Ranking\Entity\User')->equals($this->user)->setName('Same inserted user');
            $raceValidator = V::string()->equals($race);
            $teamValidator = V::instance('Ranking\Entity\Team')
                              ->attribute('player', $playerValidator)
                              ->attribute('race', $raceValidator)
                              ->setName('Team');
            $bool = V::attribute('teams', V::each($teamValidator))->assert($this->script);
            $this->assertTrue($bool);
        } catch (Argument $e) {
            $this->fail('[Validation Exception] '.PHP_EOL.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    /**
     * @dataProvider _validRace
     * @depends testAddUserWithASpecifiedRace
     * @covers Ranking\Domain\Transaction\UsersToTeams::getTeams
     */
    public function testGetTeamsWithOnlyOneTeam($race)
    {
        $team = $this->script->getTeamFromUser($this->user, $race);
        $this->script->addUser($this->user);
        $this->assertEquals(1, count($this->script->getTeams()), 'Team added not found');
    }

    /**
     * @dataProvider _validRace
     * @depends testGetTeamsWithOnlyOneTeam
     * @covers Ranking\Domain\Transaction\UsersToTeams::getIterator
     */
    public function testIteratorInterface()
    {
        $this->assertInstanceOf('IteratorAggregate', $this->script);
        $this->script->addUser($this->user);    
        foreach ($this->script as $team) {
            $this->assertTrue(V::instance('Ranking\Entity\Team')->validate($team));
        }
    }
}