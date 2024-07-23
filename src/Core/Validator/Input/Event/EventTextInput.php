<?php declare(strict_types=1);

namespace Core\Validator\Input\Event;

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
                'min' => 50,
                'max' => 8192,
                'inclusive' => true,
            ]
        );
    }
}
