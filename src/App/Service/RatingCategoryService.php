<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\RatingCategory;
use App\Table\RatingCategoryTable;
use Psr\Log\InvalidArgumentException;

class RatingCategoryService
{
    public function __construct(
        private RatingCategoryTable $table,
        private ReflectionHydrator $hydrator,
    ) {
    }

    public function findById(int $id): RatingCategory
    {
        $ratingCategory = $this->table->findById($id);

        if (!$ratingCategory) {
            throw new InvalidArgumentException('Could not find RatingCategory', 400);
        }
        return $this->hydrator->hydrate($ratingCategory, new RatingCategory());
    }

    /** @return array<RatingCategory>|null */
    public function findAll(): ?array
    {
        $ratingCategories = $this->table->findAll();

        return $this->hydrator->hydrateList($ratingCategories, RatingCategory::class);
    }
}
