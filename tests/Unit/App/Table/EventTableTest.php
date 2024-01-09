<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Event;
use App\Table\EventTable;
use Test\Unit\App\Mock\TestConstants;

/**
 * @property EventTable $table
 */
class EventTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        $this->assertSame('Event', $this->table->getTableName());
    }

    public function testCanInsertEvent(): void
    {
        $event = new Event();
        $event->setTitle(TestConstants::EVENT_CREATE_TITLE);

        $insertLastId = $this->table->insert($event);

        $this->assertSame(1, $insertLastId);
    }

    public function testCanNotInsertEvent(): void
    {
        $event = new Event();

        $insertLastId = $this->table->insert($event);

        $this->assertSame(false, $insertLastId);
    }

    public function testCanFindById(): void
    {
        $event = $this->table->findById(TestConstants::EVENT_ID);

        $this->assertSame($this->fetchResult, $event);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $event = $this->table->findById(TestConstants::EVENT_ID_UNUSED);

        $this->assertSame([], $event);
    }

    public function testCanFindAll(): void
    {
        $event = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanFindByName(): void
    {
        $event = $this->table->findByTitle(TestConstants::EVENT_TITLE);

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

    public function testCanRemoveEvent(): void
    {
        $event = new Event();
        $event->setId(TestConstants::EVENT_ID);

        $removeStatus = $this->table->remove($event);

        $this->assertSame(1, $removeStatus);
    }

    public function testCanNotRemoveEvent(): void
    {
        $event = new Event();
        $event->setId(TestConstants::EVENT_NOT_REMOVE_ID);

        $removeStatus = $this->table->remove($event);

        $this->assertSame(false, $removeStatus);
    }
}
