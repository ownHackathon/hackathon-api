<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Event;
use App\Table\EventTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class EventService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private EventTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function create(Event $event): bool
    {
        if ($this->isTopicExist($event->getName())) {
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

        if (!$event) {
            return null;
        }

        return $this->hydrator->hydrate($event, new Event());
    }

    /** @return null|Event[] */
    public function findAll(): ?array
    {
        $events = $this->table->findAll();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }

    /** @return null|Event[] */
    public function findAllActive(): ?array
    {
        $events = $this->table->findAllActive();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }

    /** @return null|Event[] */
    public function findAllNotActive(): ?array
    {
        $events = $this->table->findAllNotActive();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }

    public function isRatingCompleted(int $id): bool
    {
        $event = $this->findById($id);

        return $event->isRatingCompleted();
    }

    public function isTopicExist(string $topic): bool
    {
        $isTopic = $this->findByName($topic);

        if ($isTopic instanceof Event) {
            return true;
        }

        return false;
    }
}
