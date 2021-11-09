<?php declare(strict_types=1);

namespace App\Validator;

use App\Validator\Input\EventDescriptionInput;
use App\Validator\Input\EventDurationInput;
use App\Validator\Input\EventTextInput;
use App\Validator\Input\EventStartTimeInput;
use App\Validator\Input\EventNameInput;
use Laminas\InputFilter\InputFilter;

class EventCreateValidator extends InputFilter
{
    public function __construct(
        private EventNameInput $eventNameInput,
        private EventDescriptionInput $descriptionInput,
        private EventTextInput $eventTextInput,
        private EventStartTimeInput $startTimeInput,
        private EventDurationInput $durationInput,

    ) {
        $this->add($this->eventNameInput);
        $this->add($this->descriptionInput);
        $this->add($this->eventTextInput);
        $this->add($this->startTimeInput);
        $this->add($this->durationInput);
    }
}
