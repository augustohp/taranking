<?php
namespace Ranking\Domain\Transaction;

use \ArrayIterator;
use \IteratorAggregate;
use Respect\Validation\Validator as V;
use Ranking\Entity\User;
use Ranking\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;

class UsersToTeams implements IteratorAggregate
{
    const DEFAULT_RACE = 'Arm';
    protected $teams;

    public function __construct()
    {
        $this->teams = array();
        // Loading custom Validation Rules
        class_exists('Ranking\Validation\Rules\Team', true);
    }

    public function __toString()
    {
        return 'Ranking\Domain\Transaction\UsersToTeams: '.count($this->teams).' team(s)';
    }

    public function getIterator()
    {
        return new ArrayIterator($this->getTeams());
    }

    public static function getUserValidator($user=null) 
    {
        if (is_null($user)) {
            $validator = V::arr()->notEmpty()->setName('No teams');
        } else {
            $sameUserValidator     = V::not(V::equals($user)->setName('Same user'))->setName('Not same user');
            $notSameUserValidator  = V::attribute('player', $sameUserValidator);
            $userInstanceValidator = V::instance('Ranking\Entity\Team')->setName('Team instance');
            $teamValidator         = V::team();
            $allUserValidator      = V::allOf($userInstanceValidator,
                                              $notSameUserValidator,
                                              $teamValidator);
            $validator             = V::arr()->notEmpty()->each($allUserValidator)->setName('User in array');
        }
        return V::attribute('teams', $validator);
    }

    public function addUser(User $user, $race=self::DEFAULT_RACE)
    {
        if (count($this->teams) > 0)
            self::getUserValidator($user)->assert($this);
        $team = $this->getTeamFromUser($user, $race);
        V::team()->assert($team);
        $hash               = spl_object_hash($team);
        $this->teams[$hash] = $team;
        return $this;
    }

    public function getTeamFromUser(User $user, $race=self::DEFAULT_RACE)
    {
        $team   = new Team;
        $number = $this->getNextTeamNumber();
        $team->setPlayer($user)->setRace($race)->setNumber($number);
        return $team;
    }

    public function getNextTeamNumber()
    {
        return count($this->teams)+1;
    }

    public function getTeams()
    {
        return $this->teams;
    }
}