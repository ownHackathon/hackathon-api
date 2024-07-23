<?php declare(strict_types=1);

namespace Core\Validator\Input\Topic;

use Laminas\InputFilter\Input;

class TopicDescriptionInput extends Input
{
    public function __construct()
    {
        parent::__construct('description');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 20,
                'max' => 8096,
            ]
        );
    }
}
