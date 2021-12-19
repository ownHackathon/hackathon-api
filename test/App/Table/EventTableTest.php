<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Event;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class EventTableTest extends TestCase
{
    private EventTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('__call')->willReturnSelf();
        $select->method('fetch')->willReturn([]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new EventTable($query);

        parent::setUp();
    }

    public function testCanInsert()
    {
        $event = new Event();

        $insertEvent = $this->table->insert($event);

        $this->assertInstanceOf(EventTable::class, $insertEvent);
    }

    public function testCanFindByName()
    {
        $event = $this->table->findByName('Testname');

        $this->assertIsArray($event);
    }

    public function testCanFindAllActive()
    {
        $event = $this->table->findAllActive();

        $this->assertIsArray($event);
    }

    public function testCanFindAllNotActive()
    {
        $event = $this->table->findAllNotActive();

        $this->assertIsArray($event);
    }
}
