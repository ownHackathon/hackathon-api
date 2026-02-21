<?php declare(strict_types=1);

namespace ownHackathon\Event\Infrastructure\Hydrator;

use DateTimeImmutable;
use Exception;
use Exdrals\Core\Shared\Domain\Enum\DateTimeFormat;
use Exdrals\Core\Shared\Domain\Enum\Visibility;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use ownHackathon\Event\Domain\Enum\EventStatus;
use ownHackathon\Event\Domain\Event;
use ownHackathon\Event\Domain\EventCollection;
use ownHackathon\Shared\Domain\Event\EventCollectionInterface;
use ownHackathon\Shared\Domain\Event\EventInterface;
use ownHackathon\Shared\Infrastructure\Hydrator\EventHydratorInterface;

readonly class EventHydrator implements EventHydratorInterface
{
    public function __construct(
        private UuidFactoryInterface $uuid,
    ) {
    }

    /**
     * @throws Exception
     */
    public function hydrate(array $data): EventInterface
    {
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
            visibility: Visibility::from($data['visibility']),
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
            $collection[] = $this->hydrate($entity);
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
