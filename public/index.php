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

$router = new Respect\Rest\Router();
$router->get('/', 'Hello World!');