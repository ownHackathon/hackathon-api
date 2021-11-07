<?php declare(strict_types=1);

namespace App\Validator\Input;

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
                'inclusive' => true,
            ],
        );

        $this->getFilterChain()->attachByName('ToInt');
    }
}
