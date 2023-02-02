<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Model\Event;
use App\Service\EventService;
use App\Test\Mock\Table\MockEventTable;
use App\Test\Mock\TestConstants;

class EventServiceTest extends AbstractServiceTest
{
    private EventService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $table = new MockEventTable();
        $this->service = new EventService($table, $this->hydrator);
    }

    public function testCanNotCreate(): void
    {
        $event = new Event();
        $event->setTitle(TestConstants::EVENT_TITLE);

        $event = $this->service->create($event);

        $this->assertSame(false, $event);
    }

    public function testCanCreate(): void
    {
        $event = new Event();
        $event->setTitle(TestConstants::EVENT_CREATE_TITLE);

        $event = $this->service->create($event);

        $this->assertSame(true, $event);
    }

    public function testFindByIdThrowException(): void
    {
        $this->expectException('InvalidArgumentException');

        $this->service->findById(3);
    }

    public function testFindById(): void
    {
        $event = $this->service->findById(1);

        $this->assertInstanceOf(Event::class, $event);
    }

    public function testCanFindAll(): void
    {
        $event = $this->service->findAll();

        $this->assertIsArray($event);
        $this->assertArrayHasKey(0, $event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllActive(): void
    {
        $event = $this->service->findAllActive();

        $this->assertIsArray($event);
        $this->assertArrayHasKey(0, $event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllNotActive(): void
    {
        $event = $this->service->findAllNotActive();

        $this->assertIsArray($event);
        $this->assertArrayHasKey(0, $event);
        $this->assertInstanceOf(Event::class, $event[0]);
    }

    public function testCheckIsRatingCompleted(): void
    {
        $event = $this->service->isRatingCompleted(1);

        $this->assertSame(true, $event);
    }

    public function testCheckIsRatingNotCompleted(): void
    {
        $event = $this->service->isRatingCompleted(2);

        $this->assertSame(false, $event);
    }
}
