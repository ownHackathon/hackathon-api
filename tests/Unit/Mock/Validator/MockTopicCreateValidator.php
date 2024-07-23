<?php declare(strict_types=1);

namespace Test\Unit\Mock\Validator;

use App\Validator\Input\Topic\TopicDescriptionInput;
use App\Validator\Input\Topic\TopicInput;
use App\Validator\TopicCreateValidator;

class MockTopicCreateValidator extends TopicCreateValidator
{
    protected $data;

    public function __construct()
    {
        parent::__construct(
            new TopicInput(),
            new TopicDescriptionInput(),
        );
    }
}
