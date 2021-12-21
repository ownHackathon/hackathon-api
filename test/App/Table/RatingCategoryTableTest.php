<?php declare(strict_types=1);

namespace App\Table;

/**
 * @property RatingCategoryTable $table
 */
class RatingCategoryTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $ratingCategory = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $ratingCategory);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $ratingCategory = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $ratingCategory);
    }
}
