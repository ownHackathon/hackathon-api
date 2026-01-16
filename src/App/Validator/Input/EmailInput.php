<?php declare(strict_types=1);

namespace App\Validator\Input;

use Laminas\InputFilter\Input;
use Laminas\Validator\Hostname;

class EmailInput extends Input
{
    public function __construct()
    {
        parent::__construct('email');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'EmailAddress',
            [
                'hostnameValidator' => new Hostname(),
                'useMxCheck' => true,
            ]
        );
    }
}
