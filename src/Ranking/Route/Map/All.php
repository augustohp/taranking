<?php
namespace Ranking\Route\Map;

use Doctrine\ORM\EntityManager;
use Respect\Config\Container;
use Respect\Rest\Routable;

class All implements Routable
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
        $eName = 'Ranking\Entity\Map';
        $vars  = array('_view'=>'map/all.html');
        $repo  = $this->em->getRepository($eName);
        $dql   = "SELECT m FROM {$eName} m ORDER BY m.name ASC";
        $query = $this->em->createQuery($dql);
        $all   = $query->getResult();
        $vars['maps'] = $all;

        if ($_GET['created']) {
            $vars['notice'] = 'Map created';
        }
        return $vars;
    }
}