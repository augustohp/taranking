<?php
namespace Ranking\Route;

use Respect\Config\Container;
use Respect\Rest\Routable;

class RecentUsers implements Routable
{
    public function get()
    {
        $limit     = filter_input(INPUT_GET, 'limit');
        $limit     = $limit ?: 10;
        $doctrine  = new Container(RANKING_CONF.DS.'Doctrine.ini');
        $em        = $doctrine->entityManager;
        $dql       = 'SELECT u FROM Ranking\Entity\User u ORDER BY u.id DESC';
        $query      = $em->createQuery($dql);
        $query->setMaxResults($limit);
        $lastUsers = $query->getResult();
        
        return array('_view'=>'last_users.html', 'users'=>$lastUsers);
    }
}