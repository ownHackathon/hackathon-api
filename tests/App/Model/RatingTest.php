<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    private Rating $rating;

    protected function setUp(): void
    {
        $this->rating = new Rating();

        parent::setUp();
    }

    public function testCanSetAndGetId()
    {
        $ratingId = $this->rating->setId(1);
        $id = $ratingId->getId();

        $this->assertInstanceOf(Rating::class, $ratingId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetUserId()
    {
        $ratingUserId = $this->rating->setUserId(1);
        $userId = $ratingUserId->getUserId();

        $this->assertInstanceOf(Rating::class, $ratingUserId);
        $this->assertIsInt($userId);
        $this->assertSame(1, $userId);
    }

    public function testCanSetAndGetProjectId()
    {
        $ratingProjectId = $this->rating->setProjectId(1);
        $projectId = $ratingProjectId->getProjectId();

        $this->assertInstanceOf(Rating::class, $ratingProjectId);
        $this->assertIsInt($projectId);
        $this->assertSame(1, $projectId);
    }

    public function testCanSetAndGetEventRatingId()
    {
        $ratingEventRatingId = $this->rating->setEventRatingId(1);
        $eventRatingId = $ratingEventRatingId->getEventRatingId();

        $this->assertInstanceOf(Rating::class, $ratingEventRatingId);
        $this->assertIsInt($eventRatingId);
        $this->assertSame(1, $eventRatingId);
    }

    public function testCanSetAndGetEventRatingCategoryId()
    {
        $ratingEventRatingCategoryId = $this->rating->setEventRatingCategory(1);
        $eventRatingCategoryId = $ratingEventRatingCategoryId->getEventRatingCategory();

        $this->assertInstanceOf(Rating::class, $ratingEventRatingCategoryId);
        $this->assertIsInt($eventRatingCategoryId);
        $this->assertSame(1, $eventRatingCategoryId);
    }

    public function testCanSetAndGetRatingId()
    {
        $ratingRatingId = $this->rating->setRating(1);
        $ratingId = $ratingRatingId->getRating();

        $this->assertInstanceOf(Rating::class, $ratingRatingId);
        $this->assertIsInt($ratingId);
        $this->assertSame(1, $ratingId);
    }
}
