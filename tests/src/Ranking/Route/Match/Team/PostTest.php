<?php
namespace Ranking\Route\Match\Team;

use \DateTime;
use \DateInterval;
use \InvalidArgumentException as Argument;
use Respect\Config\Container;
use Ranking\Entity\Match;
use Ranking\Entity\Team;
use Ranking\Entity\User;
use Ranking\Entity\Map;

$header = $globals = array();
class PostTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        global $header, $globals;

        $header = $globals = array();
        $globals['creator_id'] = 1;
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::__construct
     */
    public function testConstructorWithoutArguments()
    {
        $c = new Post();
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'em', $c);
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::__construct
     * @outputBuffering disable
     */
    public function testConstructorWithEntityManager()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $c  = new Post($em);
        $this->assertAttributeEquals($em, 'em', $c);
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutUserLoggedIn()
    {
        global $globals;

        unset($globals['creator_id']);
        $c = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Team\Post::post
     */
    public function testPostWithoutPlayerInformation()
    {
        $c = new Post();
        $c->post();
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::post
     */
    public function testPostWithValidPlayerInformationAndWithoutAnyTeamInformation()
    {
        global $globals;

        $globals['teams']   = $teams   = array();
        $globals['players'] = $players = array(1, 2, 3, 4, 5, 6);
        $c                  = new Post();
        $response           = $c->post();
    }

}

function header($string) {
    global $header;

    $header[$string] = $string;
    return true;
}

function filter_input($var, $name) {
    global $globals;

    if (isset($globals[$name]))
        return $globals[$name];
    return null;
}