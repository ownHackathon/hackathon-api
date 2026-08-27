<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Validator;

use Laminas\InputFilter\InputFilter;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use ownHackathon\App\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use ownHackathon\Core\Shared\Validator\Input\VisibilityInput;

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
