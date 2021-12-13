<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class ProjectCategoryRatingTest extends TestCase
{
    private ProjectCategoryRating $projectCategoryRating;

    protected function setUp(): void
    {
        $this->projectCategoryRating = new ProjectCategoryRating();

        parent::setUp();
    }


}
