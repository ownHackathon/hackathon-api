<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Service;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Workspace\Application\Port\WorkspaceCreatorInterface;
use ownHackathon\App\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use ownHackathon\App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use ownHackathon\App\Workspace\Domain\Workspace;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;
use ownHackathon\App\Workspace\Domain\Enum\Visibility;
use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;

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
            description: $workspace->description,
            details: $workspace->details,
            visibility: Visibility::tryFrom($workspace->visibility) ?? Visibility::PUBLIC,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        try {
            $id = $this->repository->insert($workspace);
        } catch (DuplicateEntryException $e) {
            throw new WorkspaceNameAlreadyExistsException($e->getMessage(), (int)$e->getCode(), $e);
        }

        return $workspace->with(id: $id);
    }
}
