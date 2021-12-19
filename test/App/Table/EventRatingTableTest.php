<?php declare(strict_types=1);

namespace App\Table;

use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class EventRatingTableTest extends TestCase
{
    private EventRatingTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);
        $select->method('where')->willReturnSelf();
        $select->method('fetch')->willReturn([]);

        $this->table = new EventRatingTable($query);

        parent::setUp();
    }

    public function testCanFindById()
    {
        $eventRating = $this->table->findById(1);

        $this->assertIsArray($eventRating);
    }
}
