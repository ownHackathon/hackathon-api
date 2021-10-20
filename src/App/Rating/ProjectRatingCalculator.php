<?php declare(strict_types=1);

namespace App\Rating;

use App\Model\ProjectCategoryRating;

class ProjectRatingCalculator
{
    public function calculateProjectRating(array $projectCategoryRating): float
    {
        $result = 0;

        /** @var ProjectCategoryRating $rating */
        foreach ($projectCategoryRating as $rating) {
            $result += $rating->getRatingResult();
        }

        return $result / count($projectCategoryRating);
    }
}
