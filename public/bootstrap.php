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
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('PS', PATH_SEPARATOR);
define('RANKING_ENVIROMENT', getenv('RANKING_ENVIROMENT') ?: 'production');
define('RANKING_LIBRARY', __DIR__.DS.'..'.DS.'src');
define('RANKING_COMPOSER',__DIR__.DS.'..'.DS.'vendor');
define('RANKING_DOCTRINE_BIN', RANKING_COMPOSER.DS.'doctrine'.DS.'orm'.DS.'bin');
define('RANKING_SALT', getenv('RANKING_SALT') ?: '4l:8_+:7|WxsE+O+JN&w_Wr$mRc?0l88oRTD$OcJuOI^Qk&852H1)%W{yc+-BmwY');

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
    throw new RuntimeException('Dependencies not installed. See README.md.');
}

require $l;
unset($l);