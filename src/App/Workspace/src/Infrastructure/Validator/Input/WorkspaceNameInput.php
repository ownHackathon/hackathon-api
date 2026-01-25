<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;
use Laminas\Validator\Regex;

class WorkspaceNameInput extends Input
{
    public function __construct()
    {
        parent::__construct('workspaceName');

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
                'pattern' => '/^[a-zA-Z0-9 _-]+$/',
                'messages' => [
                    Regex::NOT_MATCH => 'Es sind nur Buchstaben, Leerzeichen, Unterstriche und Bindestriche erlaubt.',
                ],
            ],
        );
    }
}
