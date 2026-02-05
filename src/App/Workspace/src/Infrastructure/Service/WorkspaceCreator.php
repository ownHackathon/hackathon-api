<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Service;

use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Shared\Infrastructure\Service\SlugService;
use Exdrals\Shared\Utils\UuidFactoryInterface;
use ownHackathon\Shared\Domain\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Domain\Workspace\WorkspaceCreatorInterface;
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
        $slug = $this->slugService->getSlugFromString($workspace->name);

        $workspace = new Workspace(
            id: null,
            uuid: $this->uuid->uuid7(),
            accountId: $owner->id,
            name: $workspace->name,
            slug: $slug,
            description: $workspace->description
        );

        try {
            $id = $this->repository->insert($workspace);
        } catch (DuplicateEntryException $e) {
            throw new WorkspaceNameAlreadyExistsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        return $workspace->with(id: $id);
    }
}
