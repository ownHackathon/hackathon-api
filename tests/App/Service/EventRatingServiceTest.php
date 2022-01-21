<?php declare(strict_types=1);

namespace App\Service;

use App\Model\EventRating;
use App\Table\EventRatingTable;

class EventRatingServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(EventRatingTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new EventRatingService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(EventRatingTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new EventRatingService($table, $this->hydrator);

        $eventRating = $service->findById(1);

        $this->assertInstanceOf(EventRating::class, $eventRating);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(EventRatingTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new EventRatingService($table, $this->hydrator);

        $eventRating = $service->findAll();

        $this->assertIsArray($eventRating);
        $this->assertInstanceOf(EventRating::class, $eventRating[0]);
    }
}
