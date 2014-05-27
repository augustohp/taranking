<?php

namespace Ranking\Validation\Rules;

use Ranking\Validation\Validator as v;

class NickTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideValidNick
     */
    public function validNick($nick)
    {
        $this->assertTrue(
            v::nick()->validate($nick)
        );
    }

    public static function provideValidNick()
    {
        return array(
            array('tbon3'),
            array('stinger'),
            array('No_Mercy'),
            array('DandS')
        );
    }


    /**
     * @test
     * @dataProvider provideInvalidNick
     * @expectedException Respect\Validation\Exceptions\AllOfException
     * @expectedExceptionMessage These rules must pass for
     */
    public function invalidNick($nick)
    {
        v::nick()->assert($nick);
    }

    public static function provideInvalidNick()
    {
        return array(
            array('a'),
            array('###'),
        );
    }
}
