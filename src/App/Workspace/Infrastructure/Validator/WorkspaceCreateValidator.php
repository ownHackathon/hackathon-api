<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Validator;

use App\Policy\Domain\Enum\Visibility;
use Laminas\InputFilter\Factory;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NumberComparison;
use Laminas\Validator\Regex;

final class WorkspaceCreateValidator extends InputFilter
{
    public function __construct(Factory $factory)
    {
        parent::__construct($factory);

        $this->add([
            'type' => Input::class,
            'name' => 'name',
            'required' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => ['encoding' => 'UTF-8', 'min' => 3, 'max' => 64],
                ],
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/^[\x20-\x7E]+$/',
                        'messages' => [
                            Regex::NOT_MATCH => 'Only standard alphanumeric characters and symbols are allowed. Umlauts (ä, ö, ü, ß) are not permitted.',
                        ],
                    ],
                ],
            ],
        ]);

        $this->add([
            'type' => Input::class,
            'name' => 'description',
            'required' => false,
            'allow_empty' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => ['encoding' => 'UTF-8', 'max' => 255],
                ],
            ],
        ]);

        $this->add([
            'type' => Input::class,
            'name' => 'details',
            'required' => false,
            'allow_empty' => true,
            'filters' => [['name' => 'StringTrim']],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => ['encoding' => 'UTF-8'],
                ],
            ],
        ]);

        $this->add([
            'type' => Input::class,
            'name' => 'visibility',
            'required' => true,
            'allow_empty' => false,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'Digits'],
            ],
            'validators' => [
                [
                    'name' => NumberComparison::class,
                    'options' => [
                        'min' => Visibility::UNLISTED->value,
                        'max' => Visibility::PUBLIC->value,
                    ],
                ],
            ],
        ]);
    }
}
