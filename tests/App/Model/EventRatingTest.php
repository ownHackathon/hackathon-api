<?php declare(strict_types=1);

namespace App\Model;

use PHPUnit\Framework\TestCase;

class EventRatingTest extends TestCase
{
    private EventRating $eventRating;

    protected function setUp(): void
    {
        $this->eventRating = new EventRating();

        parent::setUp();
    }

    public function testCanSetAndGetId()
    {
        $eventRatingId = $this->eventRating->setId(1);
        $id = $eventRatingId->getId();

        $this->assertInstanceOf(EventRating::class, $eventRatingId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetMinPoints()
    {
        $eventRatingMinPoints = $this->eventRating->setMinPoints(1);
        $minPoints = $eventRatingMinPoints->getMinPoints();

        $this->assertInstanceOf(EventRating::class, $eventRatingMinPoints);
        $this->assertIsInt($minPoints);
        $this->assertSame(1, $minPoints);
    }

    public function testCanSetAndGetMaxPoints()
    {
        $eventRatingMaxPoints = $this->eventRating->setMaxPoints(1);
        $maxPoints = $eventRatingMaxPoints->getMaxPoints();

        $this->assertInstanceOf(EventRating::class, $eventRatingMaxPoints);
        $this->assertIsInt($maxPoints);
        $this->assertSame(1, $maxPoints);
    }
}
