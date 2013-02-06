<?php
use Ranking\Route\Routine\Auth;
use Ranking\Route\Routine\Twig;
use Ranking\Route\Routine\Json;
use Ranking\Entity\User;
/**
 * Routes declaration.
 *
 * Respect/Rest is used as a Front Controller to all requests. For more 
 * information on how it works head to http://github.com/Respect/Rest.
 *
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
require 'bootstrap.php';

$auth                = new Auth;
$authenticated       = function() use($auth) { return $auth(); };
$r                   = new Respect\Rest\Router();
$validUsername       = function($username) { return User::getNameValidator()->validate($username); };
$r->isAutoDispatched = false;
// Routes ----------------------------------------------------------------------

$r->get('/', 'Ranking\Route\Login');
$r->get('/users/logout', 'Ranking\Route\Logout');
$r->get('/users/login' , 'Ranking\Route\Login');
$r->post('/users/login', 'Ranking\Route\Login');
$r->get('/users/latest'  , 'Ranking\Route\RecentUsers');
$r->get('/users/register' , 'Ranking\Route\Register');
$r->post('/users/register', 'Ranking\Route\Register');
$r->get('/users/*', 'Ranking\Route\Home')->by($authenticated)->when($validUsername);
$r->get('/maps'   , 'Ranking\Route\Map\All');
$r->post('/maps'  , 'Ranking\Route\Map\Post')->by($authenticated);
$r->post('/maps/*', 'Ranking\Route\Map\One');
$r->get('/matches/create', 'Ranking\Route\Match\Create');

// Routines --------------------------------------------------------------------

// Appends API version variable and logged user identity for ALL routes
$r->always('Through', function() {
    return function($data) {
        if (!is_array($data)) {
            return $data;
        }
        $data['version']         = RANKING_VERSION;
        $userNotAlreadySetInData = !isset($data['user']);
        $userLoggedIn            = isset($_SESSION['user']);
        if ($userLoggedIn) {
            $data['user_home_url'] = '/users/'.$_SESSION['user']->getName();
        }
        if ($userLoggedIn && $userNotAlreadySetInData) {
            $data['user'] = $_SESSION['user'];
        }
        return $data;
    };
});
// Content negotiation setup for ALL routes
$r->always('Accept', array(
    'text/html'        => new Twig,
    'text/plain'       => $json = new Json,
    'application/json' => $json,
    'text/json'        => $json
));

echo $r->run();