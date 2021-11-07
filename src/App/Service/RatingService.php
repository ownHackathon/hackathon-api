<?php declare(strict_types=1);

namespace App\Service;

use App\Model\ProjectCategoryRating;
use App\Model\Rating;
use App\Table\RatingTable;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Log\InvalidArgumentException;

class RatingService
{
    use ConvertArrayToClassArrayTrait;

    public function __construct(
        private RatingTable $table,
        private ClassMethodsHydrator $hydrator
    ) {
    }

    public function findById(int $id): Rating
    {
        $rating = $this->table->findById($id);

        if (!$rating) {
            throw new InvalidArgumentException('Could not find Rating', 400);
        }
        return $this->hydrator->hydrate($rating, new Rating());
    }

    /** @return null|ProjectCategoryRating[] */
    public function findProjectCategoryRatingByProjectId(int $projectId): ?array
    {
        $projectCategoryRating = $this->table->findProjectCategoryRatingByProjectId($projectId);

        if (!$projectCategoryRating) {
            return null;
        }

        return $this->convertArrayToClassArray($projectCategoryRating, ProjectCategoryRating::class);
    }

    /** @return null|Rating[] */
    public function findAll(): ?array
    {
        $ratings = $this->table->findAll();

        if (!$ratings) {
            return null;
        }

        return $this->convertArrayToClassArray($ratings, Rating::class);
    }
}
