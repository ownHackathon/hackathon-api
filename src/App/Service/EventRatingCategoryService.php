<?php declare(strict_types=1);

namespace App\Service;

use App\Model\EventRatingCategory;
use App\Table\EventRatingCategoryTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class EventRatingCategoryService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private EventRatingCategoryTable $table,
        private ClassMethodsHydrator $hydrator
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

    /** @return null|EventRatingCategory[] */
    public function findAll(): ?array
    {
        $eventRatingCategories = $this->table->findAll();

        if (!$eventRatingCategories) {
            return null;
        }

        return $this->convertArrayToClassArray($eventRatingCategories, EventRatingCategory::class);
    }
}
