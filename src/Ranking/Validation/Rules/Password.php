<?php

namespace Ranking\Validation\Rules;

use Respect\Validation\Rules\AllOf;
use Ranking\Validation\Validator as v;

class Password extends AllOf
{
    public function __construct()
    {
        parent::__construct(
            v::string()
            ->alnum()
            ->notEmpty()
            ->length(6,45)
        );
        $this->setName('Password');
    }
}
