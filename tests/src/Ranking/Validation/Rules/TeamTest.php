<?php
namespace Ranking\Validation\Rules;

use \PHP_INT_MAX;
use Respect\Validation\Validator as V;
use Ranking\Entity\User;
use Ranking\Entity\Team as ETeam;

class TeamTest extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_RACE = 'Core';

    public function assertPreConditions()
    {
        $this->assertTrue(class_exists('Ranking\Validation\Rules\Team', true));
        $this->assertTrue(class_exists('Respect\Validation\Rules\Team', true));
    }

    public function testValidatorWithAValidTeam()
    {
        $team = new ETeam;
        $user = new User;
        $user->setId(1)->setName('testbot');
        $team->setPlayer($user)->setRace(self::DEFAULT_RACE)->setNumber(1);
        $bool = V::team()->assert($team);
        $this->assertTrue($bool);
    }

    /**
     * @expectedException Respect\Validation\Exceptions\AllOfException
     */
    public function testValidatorWithInvalidTeam()
    {
        V::team()->assert(new ETeam);
    }
}