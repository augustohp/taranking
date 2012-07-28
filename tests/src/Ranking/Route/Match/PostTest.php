<?php
namespace Ranking\Route\Match;

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
     * @covers Ranking\Route\Match\Post::__construct
     */
    public function testConstructorWithoutArguments()
    {
        $c = new Post();
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'em', $c);
    }

    /**
     * @covers Ranking\Route\Match\Post::__construct
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
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutMapInformation()
    {
        $c = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutWhenPlayedInformation()
    {
        global $globals;

        $globals['map_id'] = 1;
        $c                 = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithMoreThenAYearOldPlayedInformation()
    {
        global $globals;

        $notValidDate      = new DateTime();
        $moreThanOneYear   = new DateInterval('P1Y1D');
        $notValidDate->sub($moreThanOneYear);
        $globals['map_id'] = 1;
        $globals['played'] = $notValidDate->format(DateTime::ISO8601);
        $c                 = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithPlayedInformationInTheFuture()
    {
        global $globals;

        $notValidDate = new DateTime();
        $inTheFuture  = new DateInterval('PT10M');
        $notValidDate->add($inTheFuture);
        $globals['map_id'] = 1;
        $globals['played'] = $notValidDate->format(DateTime::ISO8601);
        $c                 = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutPlayerInformation()
    {
        global $globals;

        $validDate = new DateTime();
        $inThePast = new DateInterval('PT10M');
        $validDate->sub($inThePast);
        $globals['map_id'] = 1;
        $globals['played'] = $validDate->format(DateTime::ISO8601);
        $c                 = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithInvalidPlayerInformation()
    {
        global $globals;

        $validDate = new DateTime();
        $inThePast = new DateInterval('PT10M');
        $validDate->sub($inThePast);
        $globals['map_id']  = 1;
        $globals['played']  = $validDate->format(DateTime::ISO8601);
        $globals['players'] = array('', 'a');
        $c                  = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithoutWinnerInformation()
    {
        global $globals;

        $validDate = new DateTime();
        $inThePast = new DateInterval('PT10M');
        $validDate->sub($inThePast);
        $globals['map_id']  = 1;
        $globals['played']  = $validDate->format(DateTime::ISO8601);
        $globals['players'] = array(2, 5);
        $c                  = new Post();
        $c->post();
    }

    /**
     * @expectedException InvalidArgumentException
     * @covers Ranking\Route\Match\Post::post
     */
    public function testPostWithInvalidWinnerInformation()
    {
        global $globals;

        $validDate = new DateTime();
        $inThePast = new DateInterval('PT10M');
        $validDate->sub($inThePast);
        $globals['map_id']  = 1;
        $globals['played']  = $validDate->format(DateTime::ISO8601);
        $globals['players'] = array('', 'a');
        $globals['winner']  = 'not an id of winning team';
        $c                  = new Post();
        $c->post();
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