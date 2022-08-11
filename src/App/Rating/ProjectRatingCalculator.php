<?php declare(strict_types=1);

namespace App\Rating;

use App\Model\ProjectCategoryRating;

class ProjectRatingCalculator
{
    /** @param array<ProjectCategoryRating> $projectCategoryRating */
    public function calculateProjectRating(array $projectCategoryRating): float
    {
        $result = 0;

        foreach ($projectCategoryRating as $rating) {
            $result += $rating->getRatingResult();
        }

        return $result / count($projectCategoryRating);
    }
}
