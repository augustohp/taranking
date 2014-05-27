<?php

namespace Ranking\Validation\Rules;

use Ranking\Validation\Validator as v;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideValidPassword
     */
    public function validPassword($password)
    {
        $this->assertTrue(
            v::password()->validate($password)
        );
    }

    public static function provideValidPassword()
    {
        return array(
            array('123456'),
            array('abcdefg'),
            array('123456789012345678901234567890123456789012345'),
            array('12345 ')
        );
    }


    /**
     * @test
     * @dataProvider provideInvalidPassword
     * @expectedException Respect\Validation\Exceptions\AllOfException
     * @expectedExceptionMessage These rules must pass for
     */
    public function invalidPassword($password)
    {
        v::password()->assert($password);
    }

    public static function provideInvalidPassword()
    {
        return array(
            array(''),
            array('a'),
            array('###'),
            array('1234567890123456789012345678901234567890123456'),
        );
    }
}
