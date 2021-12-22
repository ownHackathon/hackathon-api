<?php declare(strict_types=1);

namespace App\Service;

use App\Model\RatingCategory;
use App\Table\RatingCategoryTable;

class RatingCategoryServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(RatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->willReturn(false);

        $service = new RatingCategoryService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(RatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new RatingCategoryService($table, $this->hydrator);

        $rating = $service->findById(1);

        $this->assertInstanceOf(RatingCategory::class, $rating);
    }

    public function testCanFindeAll(): void
    {
        $table = $this->createMock(RatingCategoryTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new RatingCategoryService($table, $this->hydrator);

        $rating = $service->findAll();

        $this->assertIsArray($rating);
        $this->assertInstanceOf(RatingCategory::class, $rating[0]);
    }
}
