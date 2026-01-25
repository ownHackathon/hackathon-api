<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;

class WorkspaceNameValidator extends InputFilter
{
    public function __construct(
        readonly private WorkspaceNameInput $workspaceNameInput
    ) {
        $this->add($this->workspaceNameInput);
    }
}
