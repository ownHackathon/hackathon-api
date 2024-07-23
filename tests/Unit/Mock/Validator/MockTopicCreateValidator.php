<?php declare(strict_types=1);

namespace Test\Unit\Mock\Validator;

use App\Validator\TopicCreateValidator;
use Core\Validator\Input\Topic\TopicDescriptionInput;
use Core\Validator\Input\Topic\TopicInput;

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
