<?php
use Ranking\Entity\User;
use Respect\Validation\Validator as V;
use Respect\Validation\Exceptions\AbstractNestedException as Nested;

class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Ranking\Entity\User::getId
     */
    public function testGetId()
    {
        $id    = 1;
        $user  = new User;
        $class = new ReflectionClass('Ranking\Entity\User');
        $prop  = $class->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($user, $id);
        $this->assertEquals($id, $user->getId());
    }

    /**
     * @covers Ranking\Entity\User::getName
     */
    public function testGetGetName()
    {
        $name  = 'tbon3';
        $user  = new User;
        $class = new ReflectionClass('Ranking\Entity\User');
        $prop  = $class->getProperty('name');
        $prop->setAccessible(true);
        $prop->setValue($user, $name);
        $this->assertEquals($name, $user->getName());
    }

    public function _validName()
    {
        return array(
            array('tbon3'),
            array('razor'),
            array('stinger'),
            array('racx'),
            array('bombadasso'),
            array('DandS'),
            array('no_mercy'),
            array('no-mercy'),
            array('sukkoi'),
            array('antonov')
        );
    }

    /**
     * @dataProvider _validName
     * @depends testGetGetName
     * @covers Ranking\Entity\User::setName
     */
    public function testSetName($name)
    {
        $user   = new User;
        $fluent = $user->setName($name);
        $this->assertEquals($name, $user->getName());
        $this->assertInstanceOf('Ranking\Entity\User', $fluent);
    }

    /**
     * @depends testSetName
     * @covers Ranking\Entity\User::__toString
     */
    public function testToStringConvertion()
    {
        $name          = 'testbot';
        $user          = new User;
        $validatorName = User::getNameValidator();
        $user->setName($name);
        try {
            V::attribute('name', $validatorName)->assert($user);
            V::string()->endsWith($name)->setName('toString')->assert((string) $user);
        } catch (Nested $e) {
            $this->fail('Validation Exception: '.$e->getFullMessage());
        } catch (Exception $e) {
            $this->fail('Ugly Exception: '.$e);
        }
    }

    /**
     * @covers Ranking\Entity\User::setCreated
     */
    public function testSetCreated()
    {
        $date   = new DateTime();
        $user   = new User();
        $fluent = $user->setCreated($date);
        $this->assertInstanceOf('Ranking\Entity\User', $fluent);
        $this->assertAttributeEquals($date, 'created', $user);
        return array($date, $user);
    }

    /**
     * @depends testSetCreated
     * @covers Ranking\Entity\User::getCreated
     */
    public function testGetCreated(array $args)
    {
        list($date, $user) = $args;
        $this->assertEquals($date, $user->getCreated());
    }

    /**
     * @depends testSetCreated
     * @covers Ranking\Entity\User::setCreated
     */
    public function testSetCreatedWithoutArguments()
    {
        $user = new User;
        $user->setCreated();
        $this->assertAttributeInstanceOf('DateTime', 'created', $user);
    }

    /**
     * @covers Ranking\Entity\User::setTimeZone
     */
    public function testSetTimezone()
    {
        $tz     = 'America/Sao_Paulo';
        $user   = new User();
        $fluent = $user->setTimeZone($tz);
        $this->assertInstanceOf('Ranking\Entity\User', $fluent);
        $this->assertAttributeEquals($tz, 'timezone', $user);
        return array($tz, $user);
    }

    /**
     * @depends testSetTimezone
     * @covers Ranking\Entity\User::getTimeZone
     */
    public function testGetTimezone($args)
    {
        list($tz, $user) = $args;
        $this->assertEquals($tz, $user->getTimeZone());
    }

    /**
     * @depends testGetTimezone
     * @covers Ranking\Entity\User::setTimeZone
     */
    public function testSetTimezoneWithValidString()
    {
        $string = 'America/Sao_Paulo';
        $user   = new User();
        $user->setTimeZone($string);
        $this->assertAttributeEquals($string, 'timezone', $user);
    }

    /**
     * @depends testGetTimezone
     * @covers Ranking\Entity\User::setTimeZone
     */
    public function testSetTimezoneWithValidDateTimeZoneObject()
    {
        $string = 'America/Sao_Paulo';
        $tz     = new DateTimeZone($string);
        $user   = new User();
        $user->setTimeZone($tz);
        $this->assertAttributeEquals($string, 'timezone', $user);
    }

    /**
     * @covers Ranking\Entity\User::setTimeZone
     */
    public function testSetTimezoneWithInvalidString()
    {
        $this->setExpectedException('InvalidArgumentException');
        $string = 'Whatever';
        $user   = new User();
        $user->setTimeZone($string);
    }

    /**
     * @covers Ranking\Entity\User::setCreated
     */
    public function testSetCreatedWithTimezone()
    {
        $user = new User;
        $tz   = 'America/Sao_Paulo';
        $user->setCreated(null, $tz);
        $this->assertAttributeInstanceOf('DateTime', 'created', $user);
        $this->assertAttributeEquals($tz, 'timezone', $user);
    }

    /**
     * @covers Ranking\Entity\User::getPassword
     */
    public function testGetPassword()
    {
        $passwd = 'test';
        $user  = new User;
        $class = new ReflectionClass('Ranking\Entity\User');
        $prop  = $class->getProperty('password');
        $prop->setAccessible(true);
        $prop->setValue($user, $passwd);
        $this->assertEquals($passwd, $user->getPassword());
    }

    /**
     * @covers Ranking\Entity\User::hashPassword
     */
    public function testHashPassword()
    {
        $passwd = 'test123';
        $user   = new User;
        $class  = new ReflectionClass('Ranking\Entity\User');
        $method = $class->getMethod('hashPassword');
        $method->setAccessible(true);
        $hashed = $method->invoke($user, $passwd);
        $this->assertEquals(32, strlen($hashed));
        return array($passwd, $hashed, $user);
    }

    /**
     * @depends testHashPassword
     * @covers Ranking\Entity\User::setPassword
     */
    public function testSetValidPassword($args)
    {
        list($passwd, $hashed, $user) = $args;
        $user->setPassword($passwd);
        $this->assertEquals($hashed, $user->getPassword());
    }

    public function _invalidPasswords()
    {
        return array(
            array(''),
            array(null),
            array(0),
            array('12345'),
            array('12345678901234567890123456789012345678901234567890'), // length: 50
            array('#notavalidpassword#')
        );
    }

    /**
     * @dataProvider _invalidPasswords
     * @expectedException InvalidArgumentException
     * @depends testSetValidPassword
     * @covers Ranking\Entity\User::setPassword
     */
    public function testSetInvalidPassword($passwd)
    {
        $user   = new User;
        $fluent = $user->setPassword($passwd);
        $this->assertInstanceOf('Ranking\Entity\User', $fluent);
    }

    /**
     * @depends testHashPassword
     * @covers Ranking\Entity\User::verifyPassword
     */
    public function testVerifyPassword()
    {
        $passwd = 'test123';
        $user   = new User;
        $user->setPassword($passwd);
        $this->assertTrue($user->verifyPassword($passwd));
    }

    /**
     * @covers Ranking\Entity\User::getSalt
     */
    public function testGetNewSalt()
    {
        $user = new User();
        $salt = $user->getSalt();
        $this->assertEquals(32, strlen($salt));
        $this->assertEquals($salt, $user->getSalt());
    }
}