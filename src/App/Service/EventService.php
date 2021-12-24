<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\Event;
use App\Table\EventTable;
use Psr\Log\InvalidArgumentException;

class EventService
{
    public function __construct(
        private EventTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function create(Event $event): bool
    {
        if ($this->isEventExist($event->getName())) {
            return false;
        }

        $this->table->insert($event);

        return true;
    }

    public function findById(int $id): Event
    {
        $event = $this->table->findById($id);

        if (!$event) {
            throw new InvalidArgumentException('Could not find Event', 400);
        }

        return $this->hydrator->hydrate($event, new Event());
    }

    public function findByName(string $topic): ?Event
    {
        $event = $this->table->findByName($topic);

        return $this->hydrator->hydrate($event, new Event());
    }

    /** @return null|Event[] */
    public function findAll(): ?array
    {
        $events = $this->table->findAll();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /** @return null|Event[] */
    public function findAllActive(): ?array
    {
        $events = $this->table->findAllActive();

        return $this->hydrator->hydrateList($events, Event::class);
    }

    /** @return null|Event[] */
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
        $isTopic = $this->findByName($topic);

        if ($isTopic instanceof Event) {
            return true;
        }

        return false;
    }
}
