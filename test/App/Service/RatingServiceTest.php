<?php declare(strict_types=1);

namespace App\Service;

use App\Model\ProjectCategoryRating;
use App\Model\Rating;
use App\Table\RatingTable;

class RatingServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(RatingTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->willReturn(false);

        $service = new RatingService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(RatingTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new RatingService($table, $this->hydrator);

        $rating = $service->findById(1);

        $this->assertInstanceOf(Rating::class, $rating);
    }

    public function testCanFindProjectCategoryRatingByProjectId(): void
    {
        $table = $this->createMock(RatingTable::class);

        $table->expects($this->once())
            ->method('findProjectCategoryRatingByProjectId')
            ->with(1)
            ->willReturn($this->fetchAllResult);

        $service = new RatingService($table, $this->hydrator);

        $rating = $service->findProjectCategoryRatingByProjectId(1);

        $this->assertIsArray($rating);
        $this->assertInstanceOf(ProjectCategoryRating::class, $rating[0]);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(RatingTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new RatingService($table, $this->hydrator);

        $rating = $service->findAll();

        $this->assertIsArray($rating);
        $this->assertInstanceOf(Rating::class, $rating[0]);
    }
}
