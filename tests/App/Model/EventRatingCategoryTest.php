<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class EventRatingCategoryTest extends TestCase
{
    private EventRatingCategory $eventRatingCategory;

    protected function setUp(): void
    {
        $this->eventRatingCategory = new EventRatingCategory();

        parent::setUp();
    }

    public function testCanSetAndGetId()
    {
        $eventRatingCategoryId = $this->eventRatingCategory->setId(1);
        $id = $eventRatingCategoryId->getId();

        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategoryId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetEventId()
    {
        $eventRatingCategoryEventId = $this->eventRatingCategory->setEventId(1);
        $eventIdid = $eventRatingCategoryEventId->getEventId();

        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategoryEventId);
        $this->assertIsInt($eventIdid);
        $this->assertSame(1, $eventIdid);
    }

    public function testCanSetAndGetRatingCategoryId()
    {
        $eventRatingCategoryRatingCategoryId = $this->eventRatingCategory->setRatingCategoryId(1);
        $ratingCategoryid = $eventRatingCategoryRatingCategoryId->getRatingCategoryId();

        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategoryRatingCategoryId);
        $this->assertIsInt($ratingCategoryid);
        $this->assertSame(1, $ratingCategoryid);
    }

    public function testCanSetAndGetWeighting()
    {
        $eventRatingCategoryWeighting = $this->eventRatingCategory->setWeighting(1);
        $weighting = $eventRatingCategoryWeighting->getWeighting();

        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategoryWeighting);
        $this->assertIsInt($weighting);
        $this->assertSame(1, $weighting);
    }
}
