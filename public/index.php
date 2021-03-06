<?php
/**
 * Routes declaration.
 *
 * Respect/Rest is used as a Front Controller to all requests. For more 
 * information on how it works head to http://github.com/Respect/Rest.
 *
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
require 'bootstrap.php';

$auth                = new Ranking\Route\Routine\Auth;
$authenticated       = function() use($auth) { return $auth(); };
$r                   = new Respect\Rest\Router();
$r->isAutoDispatched = false;
// Routes ----------------------------------------------------------------------

$r->get('/', 'Ranking\Route\Login');
$r->get('/users/logout', 'Ranking\Route\Logout');
$r->get('/users/login' , 'Ranking\Route\Login');
$r->post('/users/login', 'Ranking\Route\Login');
$r->get('/users/latest'  , 'Ranking\Route\RecentUsers');
$r->get('/users/register' , 'Ranking\Route\Register');
$r->post('/users/register', 'Ranking\Route\Register');
$r->get('/users/*', 'Ranking\Route\Home')->by($authenticated);
$r->get('/maps'   , 'Ranking\Route\Map\All');
$r->post('/maps'  , 'Ranking\Route\Map\Post')->by($authenticated);
$r->post('/maps/*', 'Ranking\Route\Map\One');

// Routines --------------------------------------------------------------------

// Appends API version variable and logged user identity for ALL routes
$r->always('Through', function() {
    return function($data) {
        if (!is_array($data)) {
            return $data;
        }
        $data['version'] = RANKING_VERSION;
        if (isset($_SESSION['user'])) {
            $data['user'] = $_SESSION['user'];
        }
        return $data;
    };
});
// Content negotiation setup for ALL routes
$r->always('Accept', array(
    'text/html'        => new Ranking\Route\Routine\Twig,
    'text/plain'       => $json = new Ranking\Route\Routine\Json,
    'application/json' => $json,
    'text/json'        => $json
));

echo $r->run();