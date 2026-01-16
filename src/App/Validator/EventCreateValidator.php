<?php declare(strict_types=1);

namespace App\Validator;

use App\Validator\Input\Event\EventDescriptionInput;
use App\Validator\Input\Event\EventDurationInput;
use App\Validator\Input\Event\EventStartTimeInput;
use App\Validator\Input\Event\EventTextInput;
use App\Validator\Input\Event\EventTitleInput;
use Laminas\InputFilter\InputFilter;

class EventCreateValidator extends InputFilter
{
    public function __construct(
        private readonly EventTitleInput $eventTitleInput,
        private readonly EventDescriptionInput $descriptionInput,
        private readonly EventTextInput $eventTextInput,
        private readonly EventStartTimeInput $startTimeInput,
        private readonly EventDurationInput $durationInput,
    ) {
        $this->add($this->eventTitleInput);
        $this->add($this->descriptionInput);
        $this->add($this->eventTextInput);
        $this->add($this->startTimeInput);
        $this->add($this->durationInput);
    }
}
