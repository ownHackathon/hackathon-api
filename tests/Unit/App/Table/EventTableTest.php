<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Event;
use App\Exception\DuplicateEntryException;
use App\Table\EventTable;
use Test\Unit\Mock\Database\MockQueryForCanNot;
use Test\Unit\Mock\TestConstants;

/**
 * @property EventTable $table
 */
class EventTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        self::assertSame('Event', $this->table->getTableName());
    }

    public function testCanInsertEvent(): void
    {
        $event = new Event();

        $event->setTitle(TestConstants::EVENT_CREATE_TITLE);

        $insertLastId = $this->table->insert($event);

        self::assertSame(1, $insertLastId);
    }

    public function testCanNotInsertEvent(): void
    {
        $event = new Event();

        self::expectException(DuplicateEntryException::class);

        $this->table->insert($event);
    }

    public function testCanFindById(): void
    {
        $event = $this->table->findById(TestConstants::EVENT_ID);

        self::assertSame($this->fetchResult, $event);
    }

    public function testFindByIdHasEmptyResult(): void
    {
        $event = $this->table->findById(TestConstants::EVENT_ID_UNUSED);

        self::assertSame([], $event);
    }

    public function testCanFindAll(): void
    {
        $event = $this->table->findAll();

        self::assertSame($this->fetchAllResult, $event);
    }

    public function testFindAllHasEmptyResult(): void
    {
        $table = new EventTable(new MockQueryForCanNot());

        $event = $table->findAll();

        self::assertSame([], $event);
    }

    public function testCanFindByName(): void
    {
        $event = $this->table->findByTitle(TestConstants::EVENT_TITLE);

        self::assertSame($this->fetchResult, $event);
    }

    public function testCanFindAllActive(): void
    {
        $event = $this->table->findAllActive();

        self::assertSame($this->fetchAllResult, $event);
    }

    public function testFindAllActiveHasEmptyResult(): void
    {
        $table = new EventTable(new MockQueryForCanNot());

        $event = $table->findAllActive();

        self::assertSame([], $event);
    }

    public function testCanFindAllNotActive(): void
    {
        $event = $this->table->findAllInactive();

        self::assertSame($this->fetchAllResult, $event);
    }

    public function testFindAllNotActiveHasEmptyResult(): void
    {
        $table = new EventTable(new MockQueryForCanNot());

        $event = $table->findAllInactive();

        self::assertSame([], $event);
    }

    public function testCanRemoveEvent(): void
    {
        $event = new Event();
        $event->setId(TestConstants::EVENT_ID);

        $removeStatus = $this->table->remove($event);

        self::assertSame(true, $removeStatus);
    }

    public function testCanNotRemoveEvent(): void
    {
        $event = new Event();
        $event->setId(TestConstants::EVENT_ID_NOT_REMOVED);

        $removeStatus = $this->table->remove($event);

        self::assertSame(false, $removeStatus);
    }
}
