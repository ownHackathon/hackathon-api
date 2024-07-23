<?php declare(strict_types=1);

namespace Core\Validator\Input\Event;

use Laminas\InputFilter\Input;

class EventDurationInput extends Input
{
    public function __construct()
    {
        parent::__construct('duration');

        $this->setRequired(true);

        $this->getValidatorChain()->attachByName(
            'GreaterThan',
            [
                'min' => 1,
                'max' => 356,
                'inclusive' => true,
            ],
        );

        $this->getFilterChain()->attachByName('ToInt');
    }
}
