<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\EventRatingCategory;
use App\Table\EventRatingCategoryTable;
use Psr\Log\InvalidArgumentException;

class EventRatingCategoryService
{
    public function __construct(
        private EventRatingCategoryTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): EventRatingCategory
    {
        $event = $this->table->findById($id);

        if (!$event) {
            throw new InvalidArgumentException('Could not find EventRatingCategory', 400);
        }

        return $this->hydrator->hydrate($event, new EventRatingCategory());
    }

    /** @return array<EventRatingCategory>|null */
    public function findAll(): ?array
    {
        $eventRatingCategories = $this->table->findAll();

        return $this->hydrator->hydrateList($eventRatingCategories, EventRatingCategory::class);
    }
}
