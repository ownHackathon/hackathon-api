<?php declare(strict_types=1);

namespace App\Validator\Input\Event;

use Laminas\InputFilter\Input;

class EventDescriptionInput extends Input
{
    public function __construct()
    {
        parent::__construct('description');

        $this->setRequired(false);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 10,
                'max' => 255,
                'inclusive' => true,
            ]
        );
    }
}
