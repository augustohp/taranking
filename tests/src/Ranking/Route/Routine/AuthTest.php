<?php
namespace Ranking\Route\Routine;

$header = array();
/**
 * @outputBuffering enabled
 */
class AuthTest extends \PHPUnit_Framework_TestCase
{
    
    public function tearDown()
    {
        session_destroy();
    }

    /**
     * @runInSeparateProcess
     */
    public function testAuthenticated()
    {
        $auth = new Auth;
        $_SESSION['user'] = true;
        $this->assertTrue($auth());
    }

    /**
     * @runInSeparateProcess
     */
    public function testNotAuthenticated()
    {
        global $header;

        $auth = new Auth;
        $this->assertFalse($auth());
        $this->assertGreaterThan(0, count($header));
    }
}

if (!function_exists('Ranking\Route\Routine\header')) {
    function header($string) 
    {
        global $header;
        $header[$string] = $string;
        return true;
    }
}