<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;
use Laminas\Validator\Regex;

class WorkspaceNameInput extends Input
{
    public function __construct()
    {
        parent::__construct('name');

        $this->setRequired(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'min' => 3,
                'max' => 64,
            ]
        );

        $this->getValidatorChain()->attachByName(
            'Regex',
            [
                'pattern' => '/^[\x20-\x7E]+$/',
                'messages' => [
                    Regex::NOT_MATCH => 'Only standard alphanumeric characters and symbols are allowed. Umlauts (ä, ö, ü, ß) are not permitted.',
                ],
            ],
        );
    }
}
