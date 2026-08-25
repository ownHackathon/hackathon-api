<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\Shared\Validator\Input\VisibilityInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use ownHackathon\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;

class WorkspaceCreateValidator extends InputFilter
{
    public function __construct(
        readonly private WorkspaceNameInput $workspaceNameInput,
        readonly private WorkspaceDescriptionInput $workspaceDescriptionInput,
        readonly private WorkspaceDetailsInput $workspaceDetailsInput,
        readonly private VisibilityInput $visibilityInput,
    ) {
        $this->add($this->workspaceNameInput);
        $this->add($this->workspaceDescriptionInput);
        $this->add($this->workspaceDetailsInput);
        $this->add($this->visibilityInput);
    }
}
