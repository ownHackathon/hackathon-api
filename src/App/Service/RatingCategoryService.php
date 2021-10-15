<?php declare(strict_types=1);

namespace App\Service;

use App\Model\RatingCategory;
use App\Table\RatingCategoryTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class RatingCategoryService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private RatingCategoryTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function findById(int $id): RatingCategory
    {
        $ratingCategory = $this->table->findById($id);

        if (!$ratingCategory) {
            throw new InvalidArgumentException('Could not find RatingCategory', 400);
        }
        return $this->hydrator->hydrate($ratingCategory, new Rating());
    }

    public function findAll(): ?array
    {
        $ratingCategories = $this->table->findAll();

        if (!$ratingCategories) {
            return null;
        }

        return $this->convertArrayToClassArray($ratingCategories, RatingCategory::class);
    }
}
