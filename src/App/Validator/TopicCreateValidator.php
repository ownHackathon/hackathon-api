<?php declare(strict_types=1);

namespace App\Validator;

use Core\Validator\Input\Topic\TopicDescriptionInput;
use Core\Validator\Input\Topic\TopicInput;
use Laminas\InputFilter\InputFilter;

class TopicCreateValidator extends InputFilter
{
    public function __construct(
        private readonly TopicInput $topicInput,
        private readonly TopicDescriptionInput $topicDescriptionInput,
    ) {
        $this->add($this->topicInput);
        $this->add($this->topicDescriptionInput);
    }
}
