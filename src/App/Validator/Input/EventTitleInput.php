<?php declare(strict_types=1);

namespace App\Validator\Input;

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
                'max' => 255,
                'inclusive' => true,
            ]
        );
    }
}
