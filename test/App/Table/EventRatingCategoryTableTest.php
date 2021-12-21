<?php declare(strict_types=1);

namespace App\Table;

/**
 * @property EventRatingCategoryTable $table
 */
class EventRatingCategoryTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $eventRatingCategory = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $eventRatingCategory);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $eventRatingCategory = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $eventRatingCategory);
    }
}
