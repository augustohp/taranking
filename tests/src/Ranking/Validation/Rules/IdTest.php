<?php
namespace Ranking\Validation\Rules;

use \PHP_INT_MAX;
use Respect\Validation\Validator as V;
use Ranking\Validation\Rules\Id;

class IdTest extends \PHPUnit_Framework_TestCase
{

    public function assertPreConditions()
    {
        $this->assertTrue(class_exists('Ranking\Validation\Rules\Id', true));
        $this->assertTrue(class_exists('Respect\Validation\Rules\Id', true));
    }

    public function _validId()
    {
        return array(
            array(1),
            array(2),
            array(PHP_INT_MAX)
        );       
    }

    /**
     * @covers Ranking\Validation\Rules\Id::__construct
     * @dataProvider _validId
     */
    public function testIdValidatorInsideRespectWithValidValues($id)
    {
        $bool = V::id()->assert($id);
        $this->assertTrue($bool, 'Expected that this is a valid value: '.$id);
    }
}