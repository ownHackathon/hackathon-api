<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\EventRating;
use App\Table\EventRatingTable;
use Psr\Log\InvalidArgumentException;

class EventRatingService
{
    public function __construct(
        private EventRatingTable $table,
        private ReflectionHydrator $hydrator,
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

        return $this->hydrator->hydrateList($events, EventRating::class);
    }
}
