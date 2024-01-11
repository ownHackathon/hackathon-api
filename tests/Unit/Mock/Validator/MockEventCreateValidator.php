<?php declare(strict_types=1);

namespace Test\Unit\Mock\Validator;

use App\Validator\EventCreateValidator;
use App\Validator\Input\EventDescriptionInput;
use App\Validator\Input\EventDurationInput;
use App\Validator\Input\EventStartTimeInput;
use App\Validator\Input\EventTextInput;
use App\Validator\Input\EventTitleInput;

class MockEventCreateValidator extends EventCreateValidator
{
    protected $data;

    public function __construct()
    {
        parent::__construct(
            new EventTitleInput(),
            new EventDescriptionInput(),
            new EventTextInput(),
            new EventStartTimeInput(),
            new EventDurationInput()
        );
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function isValid($context = null): bool
    {
        return $this->data[0];
    }

    public function getValues(): mixed
    {
        return $this->data;
    }
}
