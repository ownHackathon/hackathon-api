<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Service;

use DateTimeImmutable;
use ownHackathon\Core\Shared\Domain\Enum\Visibility;
use ownHackathon\Core\Shared\Domain\Exception\DuplicateEntryException;
use ownHackathon\Core\Shared\Infrastructure\Service\SlugService;
use ownHackathon\Core\Shared\Utils\UuidFactoryInterface;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\App\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\App\Shared\Infrastructure\Service\WorkspaceCreatorInterface;
use ownHackathon\App\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use ownHackathon\App\Workspace\Domain\Workspace;
use ownHackathon\App\Workspace\DTO\WorkspaceRequest;

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
