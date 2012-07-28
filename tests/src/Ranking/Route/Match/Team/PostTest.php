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
    protected $c;

    public function setUp() 
    {
        global $header, $globals;

        $header = $globals = array();
        $_SESSION['user'] = $user = new User;
        $user->setId(1);
        $globals['creator_id'] = 2;
        $em      = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $this->c = new Post($em);
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::__construct
     */
    public function testConstructorWithoutArguments()
    {
        $this->markTestIncomplete('Test failing on Travis');
        $c = new Post();
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'em', $c);
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::__construct
     * @outputBuffering disable
     */
    public function testConstructorWithEntityManager()
    {
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'em', $this->c);
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutUserLoggedIn()
    {
        global $globals;

        unset($_SESSION['user']);
        $this->c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Team\Post::post
     */
    public function testPostWithoutPlayerInformation()
    {
        $this->c->post();
    }

    /**
     * @covers Ranking\Route\Match\Team\Post::post
     */
    public function testPostWithValidPlayerInformationAndWithoutAnyTeamInformation()
    {
        global $globals;

        $globals['teams']   = $teams   = array();
        $globals['players'] = $players = array(2, 3, 4, 5, 6);
        $response           = $this->c->post();
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