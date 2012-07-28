<?php
namespace Ranking\Route\Match\Team;

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
use Ranking\Domain\Transaction\UsersToTeams;

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
        // Load custom validator into Respect namespace
        class_exists('Ranking\Validation\Rules\Id', true);
    }

    /**
     * @todo Refator user_id validator
     */
    public function post()
    {
        V::key('user', V::instance('Ranking\Entity\User')->setName('Logged user'))->assert($_SESSION);
        $logged_user_id      = $_SESSION['user']->getId();
        $user_id             = filter_input(INPUT_POST, 'creator_id');
        $players             = filter_input(INPUT_POST, 'players');
        $loggedUserValidator = V::not(V::equals($logged_user_id));
        V::id()->setName('Logged User')->assert($user_id);
        V::each(V::id())->setName('Player')->assert($players);
        V::each($loggedUserValidator)->setName('Logged user')->assert($players);
        $script = new UsersToTeams;
        foreach ($players as $user_id) {
            $user = new User;
            $user->setId($user_id);
            $script->addUser($user);
        }
    }
}