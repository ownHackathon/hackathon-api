<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Event;

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
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'startTime' => $event->getStartTime()->format('Y-m-d H:i'),
            'duration' => $event->getDuration(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn('');

        $insertEvent = $this->table->insert($event);

        $this->assertInstanceOf(EventTable::class, $insertEvent);
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
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $event = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $event);
    }

    public function testCanfindByName(): void
    {
        $this->configureSelectWithOneWhere('name', 'fakeName');

        $event = $this->table->findByName('fakeName');

        $this->assertSame($this->fetchResult, $event);
    }

    public function testCanfindAllActive(): void
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

    public function testCanfindAllNotActive(): void
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
