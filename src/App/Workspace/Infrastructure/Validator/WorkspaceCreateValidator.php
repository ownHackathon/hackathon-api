<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Validator;

use App\Policy\Http\Validator\Input\VisibilityInput;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceDescriptionInput;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceDetailsInput;
use App\Workspace\Infrastructure\Validator\Input\WorkspaceNameInput;
use Laminas\InputFilter\InputFilter;

final class WorkspaceCreateValidator extends InputFilter
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
