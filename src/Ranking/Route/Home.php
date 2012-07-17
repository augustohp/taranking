<?php
namespace Ranking\Route;

use Respect\Rest\Routable;

class Home implements Routable
{
    public function get()
    {
        $vars       = array('_view'=>'home.html');
        $registered = filter_input(INPUT_GET, 'registered');
        if ($registered) {
            header('HTTP/1.1 201 User created');
            $vars['notice'] = 'User successfully registered!';
        }
        return $vars;
    }
}