<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use ownHackathon\App\Event\Domain\Enum\EventStatus;
use ownHackathon\App\Event\Domain\Event;
use ownHackathon\App\Event\Domain\EventCollection;
use ownHackathon\App\Event\Domain\EventCollectionInterface;
use ownHackathon\App\Event\Domain\EventInterface;
use ownHackathon\App\Policy\Domain\Enum\Visibility;
use ownHackathon\Core\Clock\DateTimeFormat;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Log\LoggerInterface;

readonly final class EventHydrator implements EventHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
        private ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * @throws Exception
     */
    public function hydrate(array $data): EventInterface
    {
        $visibilityValue = $data['visibility'] ?? null;
        $visibility = is_int($visibilityValue) || is_string($visibilityValue)
            ? Visibility::tryFrom((int) $visibilityValue)
            : null;

        if ($visibility === null) {
            $this->logger?->warning(
                'Invalid event visibility; falling back to unlisted.',
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
    public function hydrateCollection(array $data): EventCollectionInterface
    {
        $collection = new EventCollection();

        foreach ($data as $entity) {
            try {
                $collection[] = $this->hydrate($entity);
            } catch (\Throwable $exception) {
                $this->logger?->warning('Invalid event persistence data skipped.', [
                    'eventId' => $entity['id'] ?? null,
                    'exception' => $exception,
                ]);
            }
        }

        return $collection;
    }

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

    public function extractCollection(EventCollectionInterface $collection): array
    {
        $data = [];

        foreach ($collection as $entity) {
            $data[] = $this->extract($entity);
        }

        return $data;
    }
}
