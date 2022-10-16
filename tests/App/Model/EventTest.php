<?php declare(strict_types=1);

namespace AppTest\Model;

use App\Enum\EventStatus;
use App\Model\Event;
use DateTime;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private Event $event;
    private string $testTime = '1000-01-01 00:00:00';

    protected function setUp(): void
    {
        $this->event = new Event();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $eventId = $this->event->setId(1);
        $id = $eventId->getId();

        $this->assertInstanceOf(Event::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $eventUserId = $this->event->setUserId(1);
        $userId = $eventUserId->getUserId();

        $this->assertInstanceOf(Event::class, $eventUserId);
        $this->assertIsInt($userId);
        $this->assertSame(1, $userId);
    }

    public function testCanSetAndGetName(): void
    {
        $eventName = $this->event->setTitle('test');
        $name = $eventName->getTitle();

        $this->assertInstanceOf(Event::class, $eventName);
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetDescription(): void
    {
        $description = $this->event->getDescription();

        $this->assertNull($description);

        $eventDescription = $this->event->setDescription('test');
        $description = $eventDescription->getDescription();

        $this->assertInstanceOf(Event::class, $eventDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }

    public function testCanSetAndGetEventText(): void
    {
        $eventEventText = $this->event->setEventText('test');
        $eventText = $eventEventText->getEventText();

        $this->assertInstanceOf(Event::class, $eventEventText);
        $this->assertIsString($eventText);
        $this->assertSame('test', $eventText);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $eventCreateTime = $this->event->setCreateTime(new DateTime($this->testTime));
        $createTime = $eventCreateTime->getCreateTime();

        $this->assertInstanceOf(Event::class, $eventCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame($this->testTime, $createTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetStartTime(): void
    {
        $eventStartTime = $this->event->setStartTime(new DateTime($this->testTime));
        $startTime = $eventStartTime->getStartTime();

        $this->assertInstanceOf(Event::class, $eventStartTime);
        $this->assertInstanceOf(DateTime::class, $startTime);
        $this->assertSame($this->testTime, $startTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetDuration(): void
    {
        $eventDuration = $this->event->setDuration(1);
        $duration = $eventDuration->getDuration();

        $this->assertInstanceOf(Event::class, $eventDuration);
        $this->assertIsInt($duration);
        $this->assertSame(1, $duration);
    }

    public function testCanSetAndGetActive(): void
    {
        $eventActive = $this->event->setStatus(EventStatus::RUNNING->value);
        $active = $eventActive->getStatus();

        $this->assertInstanceOf(Event::class, $eventActive);
        $this->assertIsInt($active);
        $this->assertSame(EventStatus::RUNNING->value, $active);
    }

    public function testCanSetAndGetRatingComplete(): void
    {
        $eventRatingComplete = $this->event->setRatingCompleted(true);
        $ratingComplete = $eventRatingComplete->isRatingCompleted();

        $this->assertInstanceOf(Event::class, $eventRatingComplete);
        $this->assertIsBool($ratingComplete);
        $this->assertSame(true, $ratingComplete);
    }
}
