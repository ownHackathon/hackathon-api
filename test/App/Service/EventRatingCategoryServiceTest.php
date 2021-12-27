<?php declare(strict_types=1);

namespace App\Service;

use App\Model\EventRatingCategory;
use App\Table\EventRatingCategoryTable;

class EventRatingCategoryServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(EventRatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new EventRatingCategoryService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(EventRatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new EventRatingCategoryService($table, $this->hydrator);

        $eventRatingCategory = $service->findById(1);

        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategory);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(EventRatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new EventRatingCategoryService($table, $this->hydrator);

        $eventRatingCategory = $service->findAll();

        $this->assertIsArray($eventRatingCategory);
        $this->assertInstanceOf(EventRatingCategory::class, $eventRatingCategory[0]);
    }
}
