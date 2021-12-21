<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;

class RatingTable extends AbstractTable
{
    public function findProjectCategoryRatingByProjectId(int $projectId): bool|array
    {
        return $this->query->from($this->table)
            ->where('projectId = ?', $projectId)
            ->select('CAST(SUM(rating)/COUNT(rating) AS UNSIGNED) AS ratingResult')
            ->select('RatingCategory.title')
            ->select('RatingCategory.description')
            ->groupBy('Rating.eventRatingCategoryId')
            ->leftJoin('EventRatingCategory ON EventRatingCategory.id = Rating.eventRatingCategoryId')
            ->leftJoin('RatingCategory ON RatingCategory.id = EventRatingCategory.ratingCategoryId')
            ->fetchAll();
    }
}
