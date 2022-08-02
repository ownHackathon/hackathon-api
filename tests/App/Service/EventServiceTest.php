<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Event;
use App\Table\EventTable;

class EventServiceTest extends AbstractServiceTest
{
    public function testCanNotCreate(): void
    {
        $table = $this->createMock(EventTable::class);

        $event = new Event();
        $event->setTitle('fakeEvent');

        $table->expects($this->once())
            ->method('findByTitle')
            ->with('fakeEvent')
            ->willReturn($this->fetchResult);

        $service = new EventService($table, $this->hydrator);

        $event = $service->create($event);

        $this->assertSame(false, $event);
    }

    public function testCanCreate(): void
    {
        $table = $this->createMock(EventTable::class);

        $event = new Event();
        $event->setTitle('fakeEvent');

        $table->expects($this->once())
            ->method('findByTitle')
            ->with('fakeEvent')
            ->willReturn(false);

        $service = new EventService($table, $this->hydrator);

        $event = $service->create($event);

        $this->assertSame(true, $event);
    }

    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(EventTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new EventService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindAll(): void
    {
        $table = $this->createMock(EventTable::class);

        $table->expects($this->once())
            ->method('findAll')
            ->willReturn($this->fetchAllResult);

        $service = new EventService($table, $this->hydrator);

        $event = $service->findAll();

        $this->assertIsArray($event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllActive(): void
    {
        $table = $this->createMock(EventTable::class);

        $table->expects($this->once())
            ->method('findAllActive')
            ->willReturn($this->fetchAllResult);

        $service = new EventService($table, $this->hydrator);

        $event = $service->findAllActive();

        $this->assertIsArray($event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllNotActive(): void
    {
        $table = $this->createMock(EventTable::class);

        $table->expects($this->once())
            ->method('findAllNotActive')
            ->willReturn($this->fetchAllResult);

        $service = new EventService($table, $this->hydrator);

        $event = $service->findAllNotActive();

        $this->assertIsArray($event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCheckIsRatingCompleted()
    {
        $table = $this->createMock(EventTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new EventService($table, $this->hydrator);

        $event = $service->isRatingCompleted(1);

        $this->assertSame(false, $event);
    }
}
