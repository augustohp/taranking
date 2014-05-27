<?php

namespace Ranking\Validation\Rules;

use Respect\Validation\Rules\AllOf;
use Ranking\Validation\Validator as v;

class Nick extends AllOf
{
    public function __construct()
    {
        $allowedChars = '-_';
        parent::__construct(
            v::string()
             ->noWhitespace()
             ->alnum($allowedChars)
             ->length(3,45)
        );
        $this->setName('Nick');
    }
}
