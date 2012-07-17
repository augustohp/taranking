<?php
namespace Ranking\Route;

use Respect\Rest\Routable;

class Home implements Routable
{
    public function get()
    {
        return array('_view'=>'home.html');
    }
}