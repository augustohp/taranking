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

$r = new Respect\Rest\Router();
$r->isAutoDispatched = false;
// Routes ----------------------------------------------------------------------

$r->get('/', function() {
    return array('_view'=>'index.html');
});

$r->get('/register', function () {
   return array('_view'=>'register.html');
});

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