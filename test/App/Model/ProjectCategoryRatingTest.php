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

    public function testCanSetAndGetTitle()
    {
        $categoryRatingTile = $this->projectCategoryRating->setTitle('test');
        $tile = $categoryRatingTile->getTitle();

        $this->assertInstanceOf(ProjectCategoryRating::class, $categoryRatingTile);
        $this->assertIsString($tile);
        $this->assertSame('test', $tile);
    }

    public function testCanSetAndGetDescription()
    {
        $categoryRatingDescription = $this->projectCategoryRating->setDescription('test');
        $description = $categoryRatingDescription->getDescription();

        $this->assertInstanceOf(ProjectCategoryRating::class, $categoryRatingDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }

    public function testCanSetAndGetRatingResult()
    {
        $categoryRatingRatingResult = $this->projectCategoryRating->setRatingResult(1);
        $ratingResult = $categoryRatingRatingResult->getRatingResult();

        $this->assertInstanceOf(ProjectCategoryRating::class, $categoryRatingRatingResult);
        $this->assertIsInt($ratingResult);
        $this->assertSame(1, $ratingResult);
    }
}
