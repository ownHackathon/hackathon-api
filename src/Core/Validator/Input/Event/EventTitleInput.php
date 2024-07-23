<?php declare(strict_types=1);

namespace Core\Validator\Input\Event;

use Laminas\InputFilter\Input;

class EventTitleInput extends Input
{
    public function __construct()
    {
        parent::__construct('title');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 50,
                'inclusive' => true,
            ]
        );
    }
}
