<?php declare(strict_types=1);

namespace App\Table;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class RatingTableTest extends TestCase
{
    private RatingTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('select')->willReturnSelf();
        $select->method('__call')->willReturnSelf();

        $select->method('fetch')->willReturn([]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new RatingTable($query);

        parent::setUp();
    }

    public function testCanFindProjectCategoryRatingByProjectId()
    {
        $rating = $this->table->findProjectCategoryRatingByProjectId(1);

        $this->assertIsArray($rating);
    }
}
