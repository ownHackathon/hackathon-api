<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Event;
use App\Service\Event\EventService;
use InvalidArgumentException;
use Test\Unit\Mock\Table\MockEventTable;
use Test\Unit\Mock\TestConstants;

class EventServiceTest extends AbstractService
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

        self::assertSame(false, $event);
    }

    public function testCanCreate(): void
    {
        $event = new Event();
        $event->setTitle(TestConstants::EVENT_CREATE_TITLE);

        $event = $this->service->create($event);

        self::assertSame(true, $event);
    }

    public function testFindByIdThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->service->findById(TestConstants::EVENT_ID_THROW_EXCEPTION);
    }

    public function testFindById(): void
    {
        $event = $this->service->findById(TestConstants::EVENT_ID);

        self::assertInstanceOf(Event::class, $event);
    }

    public function testCanFindAll(): void
    {
        $event = $this->service->findAll();

        self::assertIsArray($event);
        self::assertArrayHasKey(0, $event);
        self::assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllActive(): void
    {
        $event = $this->service->findAllActive();

        self::assertIsArray($event);
        self::assertArrayHasKey(0, $event);
        self::assertInstanceOf(Event::class, $event[0]);
    }

    public function testCanFindAllNotActive(): void
    {
        $event = $this->service->findAllNotActive();

        self::assertIsArray($event);
        self::assertArrayHasKey(0, $event);
        self::assertInstanceOf(Event::class, $event[0]);
    }

    public function testCheckIsRatingCompleted(): void
    {
        $event = $this->service->isRatingCompleted(TestConstants::EVENT_ID);

        self::assertSame(true, $event);
    }

    public function testCheckIsRatingNotCompleted(): void
    {
        $event = $this->service->isRatingCompleted(TestConstants::EVENT_ID_RATING_NOT_COMPLETED);

        self::assertSame(false, $event);
    }
}
