<?php declare(strict_types=1);

namespace App\Validator;

use App\Validator\Input\TopicDescriptionInput;
use App\Validator\Input\TopicInput;
use Laminas\InputFilter\InputFilter;

class TopicCreateValidator extends InputFilter
{
    public function __construct(
        private TopicInput $topicInput,
        private TopicDescriptionInput $topicDescriptionInput,
    ) {
        $this->add($this->topicInput);
        $this->add($this->topicDescriptionInput);
    }
}
