<?php declare(strict_types=1);

namespace App\Event\Infrastructure\Hydrator;

use App\Event\Application\Port\EventLoggerInterface;
use App\Event\Domain\Enum\EventStatus;
use App\Event\Domain\Event;
use App\Event\Domain\EventCollection;
use App\Event\Domain\EventCollectionInterface;
use App\Event\Domain\EventInterface;
use App\Event\Domain\Message\EventLogMessage;
use App\Policy\Domain\Enum\Visibility;
use Core\Clock\DateTimeFormat;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

readonly final class EventHydrator implements EventHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private EventLoggerInterface $logger,
    ) {
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function hydrate(array $data): EventInterface
    {
        $visibilityValue = $data['visibility'] ?? null;
        $visibility = is_int($visibilityValue) || is_string($visibilityValue)
            ? Visibility::tryFrom((int) $visibilityValue)
            : null;

        if ($visibility === null) {
            $this->logger->warning(
                EventLogMessage::INVALID_EVENT_VISIBILITY,
                [
                    'eventId' => $data['id'] ?? null,
                    'visibility' => $visibilityValue,
                ],
            );
            $visibility = Visibility::UNLISTED;
        }

        return new Event(
            id: $data['id'],
            uuid: $this->uuid->fromString($data['uuid']),
            workspaceId: $data['workspaceId'],
            topicId: $data['topicId'],
            name: $data['name'],
            slug: $data['slug'],
            description: $data['description'],
            details: $data['details'],
            status: EventStatus::from($data['status']),
            visibility: $visibility,
            beginsOn: new DateTimeImmutable($data['beginsOn']),
            endsOn: new DateTimeImmutable($data['endsOn']),
            createdAt: new DateTimeImmutable($data['createdAt']),
        );
    }

    /**
     * @throws Exception
     */
    #[\Override]
    public function hydrateCollection(array $data): EventCollectionInterface
    {
        $collection = new EventCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger->warning(EventLogMessage::EVENT_DATA_SKIPPED, [
                    'eventId' => $entity['id'] ?? null,
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
        'workspaceId' => 'int|null',
        'topicId' => 'int|null',
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string|null',
        'details' => 'string|null',
        'status' => 'int',
        'visibility' => 'int',
        'beginsOn' => 'string',
        'endsOn' => 'string',
        'createdAt' => 'string',
    ])]
    public function extract(EventInterface $object): array
    {
        return [
            'id' => $object->id,
            'uuid' => $object->uuid->toString(),
            'workspaceId' => $object->workspaceId,
            'topicId' => $object->topicId,
            'name' => $object->name,
            'slug' => $object->slug,
            'description' => $object->description,
            'details' => $object->details,
            'status' => $object->status->value,
            'visibility' => $object->visibility->value,
            'beginsOn' => $object->beginsOn->format(DateTimeFormat::DEFAULT->value),
            'endsOn' => $object->endsOn->format(DateTimeFormat::DEFAULT->value),
            'createdAt' => $object->createdAt->format(DateTimeFormat::DEFAULT->value),
        ];
    }

    #[\Override]
    public function extractCollection(EventCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
