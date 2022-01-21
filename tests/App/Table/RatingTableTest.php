<?php declare(strict_types=1);

namespace App\Table;

/**
 * @property RatingTable $table
 */
class RatingTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $rating = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $rating);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $rating = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $rating);
    }

    public function testCanFindProjectCategoryRatingByProjectId(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('where')
            ->with('projectId = ?', 1)
            ->willReturnSelf();

        $select->expects($this->exactly(3))
            ->method('select')
            ->withConsecutive(
                ['CAST(SUM(rating)/COUNT(rating) AS UNSIGNED) AS ratingResult'],
                ['RatingCategory.title'],
                ['RatingCategory.description']
            )
            ->willReturnSelf();

        $select->expects($this->exactly(3))
            ->method('__call')
            ->withConsecutive(
                ['groupBy', ['Rating.eventRatingCategoryId']],
                ['leftJoin', ['EventRatingCategory ON EventRatingCategory.id = Rating.eventRatingCategoryId']],
                ['leftJoin', ['RatingCategory ON RatingCategory.id = EventRatingCategory.ratingCategoryId']],
            )
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $projectCategoryRating = $this->table->findProjectCategoryRatingByProjectId(1);

        $this->assertSame($this->fetchAllResult, $projectCategoryRating);
    }
}
