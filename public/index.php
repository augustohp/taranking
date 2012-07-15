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

$r->get('/', function() {
    return array('_view'=>'index.html');
});
$r->get('/users/last', 'Ranking\Route\RecentUsers');
$r->get('/register', 'Ranking\Route\Register');
$r->post('/register', 'Ranking\Route\Register');
$r->get('/home', 'Ranking\Route\Home')->by($authenticated);

// Routines --------------------------------------------------------------------

// Appends API version variable for ALL routes
$r->always('Through', function() {
    return function($data) {
        if (is_array($data)) {
            $data['version'] = RANKING_VERSION;
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