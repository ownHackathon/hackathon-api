<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Validator;

use Laminas\InputFilter\Factory;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Hostname;

final class AuthenticationValidator extends InputFilter
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

        $this->add([
            'type' => Input::class,
            'name' => 'password',
            'required' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => ['encoding' => 'UTF-8', 'min' => 6, 'max' => 255],
                ],
            ],
        ]);
    }
}
