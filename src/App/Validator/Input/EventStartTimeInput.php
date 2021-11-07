<?php declare(strict_types=1);

namespace App\Validator\Input;

use App\Validator\DateLessNow;
use Laminas\InputFilter\Input;

class EventStartTimeInput extends Input
{
    public function __construct()
    {
        parent::__construct('startTime');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName(
            'DateTimeFormatter',
            [
                'format' => 'Y-m-d\TH:i',
            ],
        );
        $this->getValidatorChain()->attach(new DateLessNow());
    }
}
