<?php

namespace Ranking\Validation\Rules;

use Respect\Validation\Rules\AllOf;
use Ranking\Validation\Validator as v;

class NewUser extends AllOf
{
    public function __construct()
    {
        parent::__construct(
            v::arr()
             ->key('nick', v::nick())
             ->key('password1', v::password())
             ->key('password2', v::password())
        );
        $this->setName('New user');
    }
}
