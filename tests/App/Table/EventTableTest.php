<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Model\Event;
use App\Table\EventTable;

/**
 * @property EventTable $table
 */
class EventTableTest extends AbstractTableTest
{
    private const TEST_EVENT_ID = 1;

    public function testCanGetTableName(): void
    {
        $this->assertSame('Event', $this->table->getTableName());
    }


    public function testCanInsertEvent(): void
    {
        $event = new Event();

        $insertLastId = $this->table->insert($event);

        $this->assertSame(1, $insertLastId);
    }

    public function testCanFindById(): void
    {
        $event = $this->table->findById(self::TEST_EVENT_ID);

        $this->assertSame($this->fetchResult, $event);
    }

    public function testCanFindAll(): void
    {
        $event = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanFindByName(): void
    {
        $event = $this->table->findByTitle('fakeName');

        $this->assertSame($this->fetchResult, $event);
    }

    public function testCanFindAllActive(): void
    {
        $event = $this->table->findAllActive();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanFindAllNotActive(): void
    {
        $event = $this->table->findAllNotActive();

        $this->assertSame($this->fetchAllResult, $event);
    }
}
