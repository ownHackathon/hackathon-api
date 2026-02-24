<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;

class WorkspaceCreateValidator extends InputFilter
{
    public function __construct(
        readonly private WorkspaceNameInput $workspaceNameInput,
        readonly private WorkspaceDescriptionInput $workspaceDescriptionInput,
        readonly private WorkspaceDetailsInput $workspaceDetailsInput,
    ) {
        $this->add($this->workspaceNameInput);
        $this->add($this->workspaceDescriptionInput);
        $this->add($this->workspaceDetailsInput);
    }
}
