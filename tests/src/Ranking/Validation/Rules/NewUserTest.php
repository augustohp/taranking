<?php

namespace Ranking\Validation\Rules;

use Ranking\Validation\Validator as v;

class NewUserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function validNewUser()
    {
        $validPostData = array(
            'nick' => 'tbon3',
            'password1' => '123456',
            'password2' => '123456',
        );
        $this->assertTrue(
            v::newUser()->validate($validPostData)
        );
    }

    /**
     * @test
     * @expectedException Respect\Validation\Exceptions\AllOfException
     * @expectedExceptionMessage These rules must pass for
     */
    public function invalidNewUser()
    {
        $invalidPostData = array(
            'nick' => 'a',
            'password1' => '1234',
            'password2' => '1234'
        );
        v::newUser()->assert($invalidPostData);
    }
}
