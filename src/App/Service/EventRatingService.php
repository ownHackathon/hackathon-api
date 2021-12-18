<?php declare(strict_types=1);

namespace App\Service;

use App\Model\EventRating;
use App\Table\EventRatingTable;
use Laminas\Hydrator\ReflectionHydrator;
use Psr\Log\InvalidArgumentException;

class EventRatingService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private EventRatingTable $table,
        private ReflectionHydrator $hydrator
    ) {
    }

    public function findById(int $id): EventRating
    {
        $event = $this->table->findById($id);

        if (!$event) {
            throw new InvalidArgumentException('Could not find EventRating', 400);
        }

        return $this->hydrator->hydrate($event, new EventRating());
    }

    /** @return null|EventRating[] */
    public function findAll(): ?array
    {
        $events = $this->table->findAll();

        if (!$events) {
            return null;
        }

        return $this->convertArrayToClassArray($events, EventRating::class);
    }
}
