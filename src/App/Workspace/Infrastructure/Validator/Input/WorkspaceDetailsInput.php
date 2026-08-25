<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;

class WorkspaceDetailsInput extends Input
{
    public function __construct()
    {
        parent::__construct('details');

        $this->setRequired(false);
        $this->setFallbackValue('');
        $this->setAllowEmpty(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
            ]
        );
    }
}
