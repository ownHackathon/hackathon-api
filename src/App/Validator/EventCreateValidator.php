<?php declare(strict_types=1);

namespace App\Validator;

use Core\Validator\Input\Event\EventDescriptionInput;
use Core\Validator\Input\Event\EventDurationInput;
use Core\Validator\Input\Event\EventStartTimeInput;
use Core\Validator\Input\Event\EventTextInput;
use Core\Validator\Input\Event\EventTitleInput;
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
