<?php
namespace Ranking\Route\Match;

use \InvalidArgumentException as Argument;
use \PDOException;
use \Exception;
use \DateTime;
use \DateInterval;
use Doctrine\ORM\EntityManager;
use Respect\Rest\Routable;
use Respect\Config\Container;
use Respect\Validation\Validator as V;
use Ranking\Entity\Match;
use Ranking\Entity\Team;
use Ranking\Entity\User;
use Ranking\Entity\Map;

class Post implements Routable
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em=null)
    {
        if (is_null($em)) {
            $container = new Container(RANKING_CONF.DS.'Doctrine.ini');
            $em        = $container->entityManager;
        }
        $this->em = $em;
    }

    /**
     * @todo Refactor user_id validator
     */
    public function post()
    {
        $vars     = array('_view'=>'template/matches/new.html');
        $user_id  = filter_input(INPUT_POST, 'creator_id');
        $race     = filter_input(INPUT_POST, 'race');
        $race     = Team::filterRace($race);
        $map_id   = filter_input(INPUT_POST, 'map_id');
        $played   = filter_input(INPUT_POST, 'played');
        $players  = filter_input(INPUT_POST, 'players');
        $winner   = filter_input(INPUT_POST, 'winner');
        $oneYear  = new DateInterval('P1Y');
        $lastYear = new DateTime();
        $lastYear->sub($oneYear);
        $now      = new DateTime();
        $now      = $now->format(DateTime::ISO8601);
        $lastYear = $lastYear->format(DateTime::ISO8601);
        $idValidator = User::getIdValidator();
        $idValidator->setName('Logged user')->assert($user_id);
        $vars['creator_id'] = $user_id;
        Team::getRaceValidator()->assert($race);
        $vars['race'] = $race;
        $idValidator->setName('Map')->assert($map_id);
        $vars['map_id'] = $map_id;
        V::date(DateTime::ISO8601)->between($lastYear, $now)->setName('When')->assert($played);
        $vars['played'] = $played;
        V::each($idValidator)->setName('Player')->assert($players);
        $vars['players'] = $players;
        $idValidator->setName('Winner')->assert($winner);
        $vars['winners'] = $winners;

        return $vars;
    }
}