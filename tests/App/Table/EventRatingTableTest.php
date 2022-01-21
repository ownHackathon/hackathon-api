<?php declare(strict_types=1);

namespace App\Table;

/**
 * @property EventRatingTable $table
 */
class EventRatingTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $eventRating = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $eventRating);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $eventRating = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $eventRating);
    }
}
