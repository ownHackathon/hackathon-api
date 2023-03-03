<?php declare(strict_types=1);

namespace App\Test\Mock\Validator;

use App\Validator\Input\TopicDescriptionInput;
use App\Validator\Input\TopicInput;
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
