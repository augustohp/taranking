<?php
namespace Ranking\Route;

use \InvalidArgumentException as Argument;
use \RuntimeException as Runtime;
use Doctrine\ORM\EntityManager;
use Respect\Rest\Routable;
use Respect\Config\Container;
use Ranking\Entity\User;

class Login implements Routable
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em=null) 
    {
        if (is_null($em)) {
            $c        = new Container(RANKING_CONF.DS.'Doctrine.ini');
            $this->em = $c->entityManager;
        }
    }

    public function get()
    {
        return array('_view'=>'index.html');
    }

    public function post()
    {
        try {
            $vars   = array('_view'=>'index.html');
            $login  = filter_input(INPUT_POST, 'name');
            $passwd = filter_input(INPUT_POST, 'passwd');
            if (empty($login) || empty($passwd)) {
                throw new Argument('Nick/Password required to login');
            }
            $repo     = $this->em->getRepository('Ranking\Entity\User');
            $criteria = array('name'=>$login);
            $users    = $repo->findBy($criteria);
            if (empty($users)) {
                throw new Runtime('Nick+Password not found');
            }
            $userIdentity = null;
            foreach ($users as $user) {
                if (!$user->verifyPassword($passwd)) {
                    continue;
                }
                $userIdentity = $user;
                break;
            }
            if (empty($userIdentity)) {
                throw new Runtime('Nick+Password not found');
            }
            $_SESSION['user'] = $userIdentity;
            header('Location: /users/'.$userIdentity->getName());
        } catch (Argument $e) {
            $vars['alert'] = $e->getMessage();
            return $vars;
        } catch (Runtime $e) {
            $vars['alert'] = $e->getMessage();
            return $vars;
        }
        return $vars;
    }
}