<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;

class PasswordInput extends Input
{
    public function __construct()
    {
        parent::__construct('password');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 6,
                'max' => 255,
            ]
        );
    }
}
