<?php
namespace Ranking\Route\Map;

use \InvalidArgumentException as Argument;
use \PDOException;
use \Exception;
use Doctrine\ORM\EntityManager;
use Respect\Rest\Routable;
use Respect\Config\Container;
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
            $c        = new Container(RANKING_CONF.DS.'Doctrine.ini');
            $this->em = $c->entityManager;
        }
    }

    public function post()
    {
        $vars = array('_view'=>'map/all.html');
        try {
            $name = filter_input(INPUT_POST, 'name');
            $map  = new Map;
            $map->setName($name)->setCreated();

            $this->em->persist($map);
            $this->em->flush();

            header('Location: /maps?created=true');
        } catch (Argument $e) {
            $msg            = $e->getFullMessage() ?: $e->getMessage();
            $vars['alerts'] = array($msg);
            return $vars;
        } catch (PDOException $e) {
            $msg            = 'Could not insert/update map (%s)';
            $vars['alerts'] = array(sprintf($msg, $e->getMessage()));
            return $vars;
        } catch (Exception $e) {
            $msg            = 'Unknown error: %s';
            $vars['alerts'] = array(sprintf($msg, $e->getMessage()));
            return $vars;
        }
    }
}