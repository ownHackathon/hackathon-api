<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Validator\Input;

use Laminas\InputFilter\Input;

final class WorkspaceDescriptionInput extends Input
{
    public function __construct()
    {
        parent::__construct('description');

        $this->setRequired(false);
        $this->setAllowEmpty(true);

        $this->getFilterChain()->attachByName('StringTrim');

        $this->getValidatorChain()->attachByName(
            'StringLength',
            [
                'encoding' => 'UTF-8',
                'max' => 255,
            ],
        );
    }
}
