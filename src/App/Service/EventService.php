<?php
declare(strict_types=1);

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

    public function findById(int $id): Event
    {
        $event = $this->table->findById($id);

        if (!$event) {
            throw new InvalidArgumentException('Could not find Event', 400);
        }

        return $this->hydrator->hydrate($event, new Event());
    }

    public function findAll(): ?array
    {
        $events = $this->table->findAll();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }

    public function findAllActive(): ?array
    {
        $events = $this->table->findAllActive();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }

    public function findAllNotActive(): ?array
    {
        $events = $this->table->findAllNotActive();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, Event::class);
    }
}
