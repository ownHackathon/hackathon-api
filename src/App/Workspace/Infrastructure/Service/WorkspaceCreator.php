<?php declare(strict_types=1);

namespace App\Workspace\Infrastructure\Service;

use App\Account\Identity\Domain\AccountInterface;
use App\Policy\Domain\Enum\Visibility;
use App\Workspace\Application\Port\WorkspaceCreatorInterface;
use App\Workspace\Domain\Exception\WorkspaceNameAlreadyExistsException;
use App\Workspace\Domain\Repository\WorkspaceRepositoryInterface;
use App\Workspace\Domain\Workspace;
use App\Workspace\DTO\WorkspaceRequest;
use Core\SharedKernel\Domain\Exception\DuplicateEntryException;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;

readonly final class WorkspaceCreator implements WorkspaceCreatorInterface
{
    public function __construct(
        private WorkspaceRepositoryInterface $repository,
        private SlugService $slugService,
        private UuidFactoryInterface $uuid,
    ) {
    }

    #[\Override]
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
            visibility: Visibility::tryFrom($workspace->visibility) ?? Visibility::UNLISTED,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );

        try {
            $id = $this->repository->insert($workspace);
        } catch (DuplicateEntryException $e) {
            throw new WorkspaceNameAlreadyExistsException($e->getMessage(), $e->getCode(), $e);
        }

        return $workspace->with(id: $id);
    }
}
