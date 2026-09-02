<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use ownHackathon\App\Policy\Domain\Enum\Visibility;
use ownHackathon\App\Workspace\Domain\Message\WorkspaceLogMessage;
use ownHackathon\App\Workspace\Domain\Workspace;
use ownHackathon\App\Workspace\Domain\WorkspaceCollection;
use ownHackathon\App\Workspace\Domain\WorkspaceCollectionInterface;
use ownHackathon\App\Workspace\Domain\WorkspaceInterface;
use ownHackathon\Core\Clock\DateTimeFormat;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Log\LoggerInterface;

readonly final class WorkspaceHydrator implements WorkspaceHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function hydrate(array $data): WorkspaceInterface
    {
        $visibilityValue = $data['visibility'] ?? null;
        $visibility = is_int($visibilityValue) || is_string($visibilityValue)
            ? Visibility::tryFrom((int) $visibilityValue)
            : null;

        if ($visibility === null) {
            $this->logger->warning(
                WorkspaceLogMessage::INVALID_WORKSPACE_VISIBILITY,
                [
                    'workspaceId' => $data['id'] ?? null,
                    'visibility' => $visibilityValue,
                ],
            );
            $visibility = Visibility::UNLISTED;
        }

        return new Workspace(
            id: $data['id'],
            uuid: $this->uuid->fromString($data['uuid']),
            accountId: $data['accountId'],
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'],
            details: $data['details'],
            visibility: $visibility,
            createdAt: new DateTimeImmutable($data['createdAt']),
            updatedAt: new DateTimeImmutable($data['updatedAt']),
        );
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function hydrateCollection(array $data): WorkspaceCollectionInterface
    {
        $collection = new WorkspaceCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger->warning(WorkspaceLogMessage::WORKSPACE_DATA_SKIPPED, [
                    'workspaceId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
        }

        return $collection;
    }

    #[\Override]
    #[ArrayShape([
        'id' => 'int|null',
        'uuid' => 'string',
        'accountId' => 'int',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string|null',
        'details' => 'string|null',
        'visibility' => 'int',
        'createdAt' => 'string',
        'updatedAt' => 'string',
    ])]
    public function extract(WorkspaceInterface $object): array
    {
        return [
            'id' => $object->id,
            'uuid' => $object->uuid->toString(),
            'accountId' => $object->accountId,
            'name' => $object->name,
            'slug' => $object->slug,
            'description' => $object->description,
            'details' => $object->details,
            'visibility' => $object->visibility->value,
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
            'updatedAt' => $object->updatedAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    #[\Override]
    public function extractCollection(WorkspaceCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
