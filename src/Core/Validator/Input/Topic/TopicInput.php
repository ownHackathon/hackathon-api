<?php declare(strict_types=1);

namespace Core\Validator\Input\Topic;

use Laminas\InputFilter\Input;

class TopicInput extends Input
{
    public function __construct()
    {
        parent::__construct('topic');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 50,
            ]
        );
    }
}
