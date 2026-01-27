<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Service;

use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Infrastructure\Service\SlugService;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCreatorInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use ownHackathon\Workspace\Domain\Workspace;
use ownHackathon\Workspace\DTO\WorkspaceRequest;

readonly class WorkspaceCreator implements WorkspaceCreatorInterface
{
    public function __construct(
        private WorkspaceRepositoryInterface $repository,
        private SlugService $slugService,
        private UuidFactoryInterface $uuid
    ) {
    }

    public function create(WorkspaceRequest $workspace, AccountInterface $owner): Workspace
    {
        $slug = $this->slugService->getSlugFromString($workspace->workspaceName);

        $workspace = new Workspace(
            id: null,
            uuid: $this->uuid->uuid7(),
            accountId: $owner->id,
            name: $workspace->workspaceName,
            slug: $slug,
        );

        try {
            $id = $this->repository->insert($workspace);
        } catch (DuplicateEntryException $e) {
            throw new WorkspaceNameAlreadyExistsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        return $workspace->with(id: $id);
    }
}
