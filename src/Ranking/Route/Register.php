<?php
namespace Ranking\Route;

use \Exception;
use \InvalidArgumentException as Argument;
use \PDOException;
use Respect\Rest\Routable;
use Ranking\Entity\User;
use Respect\Config\Container;

class Register implements Routable
{
    public function get()
    {
        return array('_view'=>'register.html');
    }

    public function post()
    {

        $name    = filter_input(INPUT_POST, 'name');
        $passwd1 = filter_input(INPUT_POST, 'passwd1');
        $passwd2 = filter_input(INPUT_POST, 'passwd2');
        $vars    = array('_view'=>'register.html');
        $vars['name'] = $name;
        try {
            if (strcmp($passwd1, $passwd2) !== 0 ) {
                throw new Argument('The passwords must be the same');
            }
            $doctrine = new Container(RANKING_CONF.DS.'Doctrine.ini');
            $em       = $doctrine->entityManager;
            $user     = new User();
            $user->setName($name);
            $user->setPassword($passwd1);
            $user->setCreated(null, 'America/Sao_Paulo');
            $em->persist($user);
            $em->flush();
            
            // Everything ok, log user
            $_SESSION['user'] = $user;
            header('HTTP/1.1 201 User created');
            header('Location: /home');
        } catch (Respect\Validation\Exceptions\ValidationException $e) {
            $vars['alerts'] = $e->getFullMessage();
            return $vars;
        } catch (PDOException $e) {
            $vars['alerts'] = 'User already exists';
            return $vars;
        } catch (Exception $e) {
            $vars['alerts'] = $e->getMessage();
            return $vars;
        }
    }
}