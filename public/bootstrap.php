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
if (defined('RANKING_SALT')) {
    return; // Prevent (PHPUnit) from loading this file again and again
}
date_default_timezone_set('UTC');

// Include Composer's Autoload -------------------------------------------------
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('PS', PATH_SEPARATOR);
define('RANKING_VERSION', '0.1.0');
define('RANKING_ROOT', realpath(__DIR__.DS.'..'));
define('RANKING_LIBRARY', RANKING_ROOT.DS.'src');
define('RANKING_CONF', RANKING_ROOT.DS.'conf');
define('RANKING_COMPOSER',RANKING_ROOT.DS.'vendor');
define('RANKING_DOCTRINE_BIN', RANKING_COMPOSER.DS.'doctrine'.DS.'orm'.DS.'bin');
if (!file_exists($l=RANKING_COMPOSER.DS.'autoload.php')) {
    throw new RuntimeException('Dependencies not installed. See README.md.');
}
require $l;
unset($l);

// Configuration ---------------------------------------------------------------
$env = new Ranking\Enviroment();
define('RANKING_ENVIROMENT', $env->getName());
define('RANKING_SALT', $env->getSalt());
//                                                                      Database
define('RANKING_DB_HOST', $env->getDatabaseHost() ?: '127.0.0.1');
define('RANKING_DB_USER', $env->getDatabaseUser() ?: 'root');
define('RANKING_DB_PASSWD', $env->getDatabasePasswd() ?: 'root');
define('RANKING_DB_NAME', $env->getDatabaseName() ?: 'ranking');
define('RANKING_DB_DRIVER', $env->getDatabaseDriver() ?: 'pdo_mysql');
define('RANKING_DOCTRINE_PROXY_DIR', RANKING_LIBRARY.DS.'Ranking'.DS.'Proxy');

// Enviroment-aware configurations ---------------------------------------------
ini_set('register_globals', 0);
ini_set('error_reporting', -1);
switch (RANKING_ENVIROMENT) {
    case 'development':
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        ini_set('memory_limit', '32M');
        break;
    case 'production':
    default:
        ini_set('memory_limit', '16M');
        ini_set('display_errors', 0);
        break;
}

// Buil-in web server
if (php_sapi_name() == 'cli-server') {
    // Replicate the effects of basic "index.php"-hiding mod_rewrite rules
    // Tested working under FatFreeFramework 2.0.6 through 2.0.12.
    $_SERVER['SCRIPT_NAME'] = str_replace(__DIR__, '', __FILE__);
    $_SERVER['SCRIPT_FILENAME'] = __FILE__;

    // Replicate the FatFree/WordPress/etc. .htaccess "serve existing files" bit
    $url_parts = parse_url($_SERVER["REQUEST_URI"]);
    $_req = rtrim($_SERVER['DOCUMENT_ROOT'] . $url_parts['path'], '/' . DIRECTORY_SEPARATOR);
    if (__FILE__ !== $_req && __DIR__ !== $_req && file_exists($_req)) {
        return false;    // serve the requested resource as-is.
    }
    require 'index.php';
}