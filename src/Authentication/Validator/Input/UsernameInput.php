<?php
declare(strict_types=1);

namespace Authentication\Validator\Input;

use Laminas\InputFilter\Input;

class UsernameInput extends Input
{
    public function __construct()
    {
        parent::__construct('userName');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');
    }
}
