<?php

namespace Ranking\Validation;

use Ranking\Validation\Validator as v;

/**
 * @group validation
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage Rule "batman" does not exists in namespaces: Respect\Validation\Rules, Ranking\Validation\Rules
     */
    public function nonExistingRule()
    {
        v::batman();
    }

    /**
     * @test
     */
    public function buildRespectRule()
    {
        $this->assertInstanceOf(
            'Respect\Validation\Validator',
            v::bool()
        );
        $this->assertTrue(v::bool()->validate(true));
    }
}
