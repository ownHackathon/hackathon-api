<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Infrastructure\Service;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Visibility;
use Exdrals\Core\Shared\Domain\Exception\DuplicateEntryException;
use Exdrals\Core\Shared\Infrastructure\Service\SlugService;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Exdrals\Identity\Domain\AccountInterface;
use ownHackathon\Shared\Infrastructure\Persistence\Repository\WorkspaceRepositoryInterface;
use ownHackathon\Shared\Infrastructure\Service\WorkspaceCreatorInterface;
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
            description: $workspace->description,
            details: $workspace->details,
            visibility: Visibility::tryFrom($workspace->visibility),
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
