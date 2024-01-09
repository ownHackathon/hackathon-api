<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Event;
use App\Hydrator\ReflectionHydrator;
use App\Table\EventTable;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Log\InvalidArgumentException;

class EventService
{
    public function __construct(
        private readonly EventTable $table,
        private readonly ReflectionHydrator $hydrator,
    ) {
    }

    public function create(Event $event): bool
    {
        if ($this->isEventExist($event->getTitle())) {
            return false;
        }

        $this->table->insert($event);

        return true;
    }

    public function findById(int $id): Event
    {
        $event = $this->table->findById($id);

        if ($event === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find Event with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($event, new Event());
    }

    public function findByTitle(string $topic): ?Event
    {
        $event = $this->table->findByTitle($topic);

        return $this->hydrator->hydrate($event, new Event());
    }

    /**
     * @return array<Event>
     */
    public function findAll(string $order = 'startTime', string $sort = 'DESC'): array
    {
        $events = $this->table->findAll($order, $sort);

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /**
     * @return array<Event>|null
     */
    public function findAllActive(): ?array
    {
        $events = $this->table->findAllActive();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /**
     * @return array<Event>|null
     */
    public function findAllNotActive(): ?array
    {
        $events = $this->table->findAllNotActive();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    public function isRatingCompleted(int $id): bool
    {
        $event = $this->findById($id);

        return $event->isRatingCompleted();
    }

    public function isEventExist(string $topic): bool
    {
        $event = $this->findByTitle($topic);

        return $event instanceof Event;
    }
}
