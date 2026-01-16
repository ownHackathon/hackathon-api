<?php declare(strict_types=1);

namespace Core\Validator\Input;

use Laminas\InputFilter\Input;

class UsernameInput extends Input
{
    public function __construct()
    {
        parent::__construct('username');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 50,
            ]
        );
    }
}
