<?php declare(strict_types=1);

namespace App\Validator\Input;

use Laminas\InputFilter\Input;

class EventTextInput extends Input
{
    public function __construct()
    {
        parent::__construct('eventText');

        $this->setRequired(false);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'max' => 8192,
                'inclusive' => true,
            ]
        );
    }
}
