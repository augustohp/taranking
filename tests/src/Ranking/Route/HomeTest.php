<?php
namespace Ranking\Route;

use \InvalidArgumentException as Argument;
use \Exception;
use Ranking\Entity\User;
use Doctrine\ORM\EntityManager;
use Respect\Config\Container;
use Respect\Validation\Validator as V;

$header = $global = array();
class HomeTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        global $header, $global;
        $header = $global = array();
    }

    /**
     * @covers Ranking\Route\Home::__construct
     */
    public function testConstructWithoutArguments()
    {
        $c = new Home();
        $this->markTestIncomplete('Test failing on Travis');
        $this->assertAttributeInstanceOf('Doctrine\ORM\EntityManager', 'em', $c);
    }

    /**
     * @covers Ranking\Route\Home::__construct
     */
    public function testConstructWithEntityManagerArgument()
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array(), array(), '', false);
        $c  = new Home($em);
        $this->assertAttributeEquals($em, 'em', $c);
    }

    protected function _getEntityManagerForRepositoryMock($entity, $repo)
    {
        $em = $this->getMock('Doctrine\ORM\EntityManager', array('getRepository'), array(), '', false);
        $em->expects($this->once())
           ->method('getRepository')
           ->with($this->equalTo($entity))
           ->will($this->returnValue($repo));
        return $em;
    }

    protected function _getRepositoryFindByOneMock($criteria, $return)
    {
        $repo = $this->getMock('Doctrine\ORM\EntityRepository', array('findOneBy'), array(), '', false);
        $repo->expects($this->once())
             ->method('findOneBy')
             ->with($this->equalTo($criteria))
             ->will($this->returnValue($return));   
        return $repo;
    }

    protected function _getEntityManagerForOneUserMock($username)
    {
        $user     = new User(); 
        $user->setName($username);
        $criteria = array('name'=>$username);
        $repo     = $this->_getRepositoryFindByOneMock($criteria, $user);
        $em       = $this->_getEntityManagerForRepositoryMock('Ranking\Entity\User', $repo);
        return $em;
    }

    /**
     * @covers Ranking\Route\Home::get
     */
    public function testGetValidUserName()
    {
        $username = 'tbon3';
        $em       = $this->_getEntityManagerForOneUserMock($username);
        $c        = new Home($em);
        $vars     = $c->get($username);
        $this->assertEquals($username, $vars['user']->getName());
        $this->assertEquals('home.html', $vars['_view']);
    }

    protected function _getEntityManagerForUserNotFound($username)
    {
        $user     = null;
        $criteria = array('name'=>$username);
        $repo     = $this->_getRepositoryFindByOneMock($criteria, $user);
        $em       = $this->_getEntityManagerForRepositoryMock('Ranking\Entity\User', $repo);
        return $em;
    }

    /**
     * @covers Ranking\Route\Home::get
     */
    public function testGetNonExistingUsername()
    {
        global $header;
        $username = 'testbot';
        $em       = $this->_getEntityManagerForUserNotFound($username);
        $c        = new Home($em);
        $vars     = $c->get($username);
        try {
            V::arr()->key('_view', V::equals('404.html'))->assert($vars);
            V::arr()->key('msg')->assert($vars);
            V::in($header)
             ->setName('HTTP header')
             ->assert(Home::HTTP_USER_NOT_FOUND);
        } catch (Argument $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }

    public function testGetExistingUserRecentlyCreated()
    {
        global $header, $global;
        $global['registered'] = true;
        $username        = 'testbot';
        $em              = $this->_getEntityManagerForOneUserMock($username);
        $c               = new Home($em);
        $vars            = $c->get($username);
        try {
            V::arr()->key('_view', V::equals('home.html'))->assert($vars);
            V::arr()->key('user')->assert($vars);
            V::arr()->contains(Home::HTTP_USER_CREATED)->assert($header);
        } catch (Argument $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e->getMessage());
        }
    }
}

function header($string)
{
    global $header;
    $header[$string] = $string;
    return true;
}

function filter_input($var, $name)
{
    global $global;
    if (isset($global[$name]))
        return $global[$name];

    return null;
}