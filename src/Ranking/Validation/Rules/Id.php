<?php
namespace Ranking\Validation\Rules;

use \PHP_INT_MAX;
use Respect\Validation\Rules\Int;
use Respect\Validation\Rules\Between;

class Id extends Between
{
    public function __construct()
    {
        $min       = 1;
        $max       = PHP_INT_MAX;
        $inclusive = true;
        parent::__construct($min, $max, $inclusive);
        $this->addRule(new Int);
    }
}
class_alias('Ranking\Validation\Rules\Id', 'Respect\Validation\Rules\Id');