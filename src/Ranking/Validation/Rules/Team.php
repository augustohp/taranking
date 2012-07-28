<?php
namespace Ranking\Validation\Rules;

use \PHP_INT_MAX;
use Respect\Validation\Rules\AllOf as BaseRule;
use Respect\Validation\Validator as V;
use Ranking\Entity\Team as ETeam;

class Team extends BaseRule
{
    public function __construct()
    {
        $instance = V::instance('Ranking\Entity\Team')->setName('Player instance');
        $race     = V::attribute('race', ETeam::getRaceValidator())->setName('Player race');
        $player   = V::attribute('player', V::instance('Ranking\Entity\User'))->setName('Team user instance');
        $number   = V::attribute('number', ETeam::getNumberValidator())->setName('Team number');
        //$id       = V::when(V::notEmpty(), new Id, true)->setName('Team id');
        //$match    = V::when(V::notEmpty(), V::instance('Ranking\Entity\Match'), true)->setName('Team match');
        parent::__construct($instance, $race, $player, $number);
        $this->setName('Team instance');
        
    }
}
class_alias('Ranking\Validation\Rules\Team', 'Respect\Validation\Rules\Team');