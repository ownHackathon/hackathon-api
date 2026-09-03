<?php declare(strict_types=1);

namespace App\Mailing\Infrastructure\Validator;

use Laminas\InputFilter\Factory;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Hostname;

final class EMailValidator extends InputFilter
{
    public function __construct(Factory $factory)
    {
        parent::__construct($factory);

        $this->add([
            'type' => Input::class,
            'name' => 'email',
            'required' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'hostnameValidator' => new Hostname(),
                        'useMxCheck' => true,
                    ],
                ],
            ],
        ]);
    }
}
