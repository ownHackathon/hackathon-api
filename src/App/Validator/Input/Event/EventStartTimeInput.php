<?php declare(strict_types=1);

namespace App\Validator\Input\Event;

use Core\Validator\DateLessNow;
use Laminas\InputFilter\Input;
use Laminas\Validator\Date;

class EventStartTimeInput extends Input
{
    public function __construct()
    {
        parent::__construct('startedAt');

        $this->setRequired(true);
        $this->setBreakOnFailure(true);

        $this->getValidatorChain()->attach(
            new Date([
                'format' => 'Y-m-d H:i:s',
                'strict' => true,
            ]),
        );

        $this->getValidatorChain()->attach(new DateLessNow());
    }
}
