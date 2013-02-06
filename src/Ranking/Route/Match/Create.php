<?php
namespace Ranking\Route\Match;

use Respect\Rest\Routable;
use Respect\Config\Container;
use Doctrine\ORM\EntityManager;

class Create implements Routable
{
    public function get()
    {
        return array('_view'=>'matches/create.html');
    }
}