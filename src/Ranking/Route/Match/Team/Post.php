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
     * @todo Refator user_id validator
     */
    public function post()
    {
        $user_id  = filter_input(INPUT_POST, 'creator_id');
        $players  = filter_input(INPUT_POST, 'players');
        $idValidator = User::getIdValidator();
        $idValidator->setName('Logged User')->assert($user_id);
        V::each($idValidator)->setName('Player')->assert($players);
    }
}