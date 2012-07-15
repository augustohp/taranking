<?php
namespace Ranking\Route\Routine;

use \PHP_SESSION_ACTIVE;

/**
 * Authenticated routes routine.
 *
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
class Auth
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    public function __invoke()
    {
        if (isset($_SESSION['user']))
            return true;

        header('HTTP/1.1 403 You must be authenticated to access that URL');
        header('Location: /');
        return false;
    }
}