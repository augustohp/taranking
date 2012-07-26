<?php
namespace Ranking\Route;

use Respect\Rest\Routable;
use Respect\Config\Container;
use Doctrine\ORM\EntityManager;

class Home implements Routable
{
    const HTTP_USER_NOT_FOUND = 'HTTP/1.1 404 User not found';
    const HTTP_USER_CREATED = 'HTTP/1.1 201 User created';
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

    public function get($username)
    {
        $vars       = array('_view'=>'home.html');
        $registered = filter_input(INPUT_GET, 'registered');
        $repo       = $this->em->getRepository('Ranking\Entity\User');
        $criteria   = array('name'=>$username);
        $user       = $repo->findOneBy($criteria);
        if (empty($user)) {
            header(self::HTTP_USER_NOT_FOUND);
            return array('_view'=>'404.html', 'msg'=>'User not found');
        }
        $vars['user'] = $user;
        if ($registered) {
            header(self::HTTP_USER_CREATED);
            $vars['notice'] = 'User successfully registered!';
        }
        return $vars;
    }
}