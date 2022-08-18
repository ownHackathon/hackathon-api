<?php declare(strict_types=1);

namespace AppTest\Table;

use App\Model\Event;
use App\Table\EventTable;

/**
 * @property EventTable $table
 */
class EventTableTest extends AbstractTableTest
{
    public function testCanInsertEvent(): void
    {
        $event = new Event();
        $values = [
            'userId' => $event->getUserId(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn(1);

        $insertLastId = $this->table->insert($event);

        $this->assertSame(1, $insertLastId);
    }

    public function testCanNotInsertEvent(): void
    {
        $event = new Event();
        $values = [
            'userId' => $event->getUserId(),
            'title' => $event->getTitle(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn(0);

        $insertLastId = $this->table->insert($event);

        $this->assertSame(0, $insertLastId);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $event = $this->table->findById(1);

        $this->assertSame($this->fetchResult, $event);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('__call')
            ->with('orderBy', ['startTime ASC'])
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $event = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanFindByName(): void
    {
        $this->configureSelectWithOneWhere('title', 'fakeName');

        $event = $this->table->findByTitle('fakeName');

        $this->assertSame($this->fetchResult, $event);
    }

    public function testCanFindAllActive(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('where')
            ->with('active', 1)
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('__call')
            ->with('orderBy', ['startTime DESC'])
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $event = $this->table->findAllActive();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanFindAllNotActive(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('where')
            ->with('active', 0)
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('__call')
            ->with('orderBy', ['startTime DESC'])
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $event = $this->table->findAllNotActive();

        $this->assertSame($this->fetchAllResult, $event);
    }
}
