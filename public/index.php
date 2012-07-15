<?php
/**
 * Total Annhilation Ranking application boostrap.
 *
 * Basic PHP application configuration and bootstraping. All classes are PSR-0
 * compliend and Composer (http://getcomposer.org) is used to manage dependencies.
 *
 * @since 0.1.0
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
date_default_timezone_set('UTC');

// Constants
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('RANKING_ENVIROMENT', getenv('RANKING_ENVIROMENT') ?: 'production');
define('RANKING_LIBRARY', __DIR__.DS.'..'.DS.'src');
define('RANKING_COMPOSER',__DIR__.DS.'..'.DS.'vendor');

// Enviroment-aware configurations
ini_set('register_globals', 0);

switch (RANKING_ENVIROMENT) {
    case 'development':
        ini_set('display_errors', 1);
        break;
    case 'production':
    default:
        ini_set('display_errors', 0);
        break;
}

// Include Composer's Autoload
if (!file_exists($l=RANKING_COMPOSER.DS.'autoload.php')) {
    throw new RuntimeException('Dependencies not installed, see README for installation instructions');
}
require $l;

$router = new Respect\Rest\Router();
$router->get('/', 'Hello World!');