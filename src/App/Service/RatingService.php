<?php declare(strict_types=1);

namespace App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Model\ProjectCategoryRating;
use App\Model\Rating;
use App\Table\RatingTable;
use Psr\Log\InvalidArgumentException;

class RatingService
{
    public function __construct(
        private RatingTable $table,
        private ReflectionHydrator $hydrator,
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

        return $this->hydrator->hydrateList($projectCategoryRating, ProjectCategoryRating::class);
    }

    /** @return null|Rating[] */
    public function findAll(): ?array
    {
        $ratings = $this->table->findAll();

        return $this->hydrator->hydrateList($ratings, Rating::class);
    }
}
