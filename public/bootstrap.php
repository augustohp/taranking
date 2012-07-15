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

// Constants -------------------------------------------------------------------
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('PS', PATH_SEPARATOR);
define('RANKING_VERSION', '0.1.0');
define('RANKING_ENVIROMENT', getenv('RANKING_ENVIROMENT') ?: 'development');
define('RANKING_ROOT', realpath(__DIR__.DS.'..'));
define('RANKING_LIBRARY', RANKING_ROOT.DS.'src');
define('RANKING_CONF', RANKING_ROOT.DS.'conf');
define('RANKING_COMPOSER',RANKING_ROOT.DS.'vendor');
define('RANKING_DOCTRINE_BIN', RANKING_COMPOSER.DS.'doctrine'.DS.'orm'.DS.'bin');
define('RANKING_SALT', getenv('RANKING_SALT') ?: '4l:8_+:7|WxsE+O+JN&w_Wr$mRc?0l88oRTD$OcJuOI^Qk&852H1)%W{yc+-BmwY');
/**
 * Database configuration constants
 */
define('RANKING_DB_HOST', getenv('RANKING_DB_HOST') ?: '');
define('RANKING_DB_USER', getenv('RANKING_DB_USER') ?: '');
define('RANKING_DB_PASSWD', getenv('RANKING_DB_PASSWD') ?: '');
define('RANKING_DB_NAME', getenv('RANKING_DB_NAME') ?: '');
define('RANKING_DB_DRIVER', getenv('RANKING_DB_DRIVER') ?: 'pdo_sqlite');
define('RANKING_DB_DRIVER', getenv('RANKING_DB_PORT') ?: '3306');
define('RANKING_DOCTRINE_PROXY_DIR', RANKING_LIBRARY.DS.'Ranking'.DS.'Proxy');

// Enviroment-aware configurations ---------------------------------------------
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

// Include Composer's Autoload -------------------------------------------------
if (!file_exists($l=RANKING_COMPOSER.DS.'autoload.php')) {
    throw new RuntimeException('Dependencies not installed. See README.md.');
}

require $l;
unset($l);