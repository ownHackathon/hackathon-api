<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;

class AccountNameInput extends Input
{
    public function __construct()
    {
        parent::__construct('accountName');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 64,
            ]
        );
    }
}
