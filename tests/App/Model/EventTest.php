<?php declare(strict_types=1);

namespace AppTest\Model;

use App\Enum\DateTimeFormat;
use App\Enum\EventStatus;
use App\Model\Event;
use DateTime;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private const TEST_USER_ID = 1;
    private const TEST_TIME = '1000-01-01 00:00';
    private const TEST_TITLE = 'Test Title';
    private const TEST_DESCRIPTION = 'Test Description';
    private const TEST_EVENT_TEXT = 'Test Event Text';
    private const TEST_DURATION = 1;

    private Event $event;

    protected function setUp(): void
    {
        $this->event = new Event();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $eventId = $this->event->setId(self::TEST_USER_ID);
        $id = $eventId->getId();

        $this->assertInstanceOf(Event::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(self::TEST_USER_ID, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $eventUserId = $this->event->setUserId(self::TEST_USER_ID);
        $userId = $eventUserId->getUserId();

        $this->assertInstanceOf(Event::class, $eventUserId);
        $this->assertIsInt($userId);
        $this->assertSame(self::TEST_USER_ID, $userId);
    }

    public function testCanSetAndGetName(): void
    {
        $eventName = $this->event->setTitle(self::TEST_TITLE);
        $name = $eventName->getTitle();

        $this->assertInstanceOf(Event::class, $eventName);
        $this->assertIsString($name);
        $this->assertSame(self::TEST_TITLE, $name);
    }

    public function testCanSetAndGetDescription(): void
    {
        $description = $this->event->getDescription();

        $this->assertNull($description);

        $eventDescription = $this->event->setDescription(self::TEST_DESCRIPTION);
        $description = $eventDescription->getDescription();

        $this->assertInstanceOf(Event::class, $eventDescription);
        $this->assertIsString($description);
        $this->assertSame(self::TEST_DESCRIPTION, $description);
    }

    public function testCanSetAndGetEventText(): void
    {
        $eventEventText = $this->event->setEventText(self::TEST_EVENT_TEXT);
        $eventText = $eventEventText->getEventText();

        $this->assertInstanceOf(Event::class, $eventEventText);
        $this->assertIsString($eventText);
        $this->assertSame(self::TEST_EVENT_TEXT, $eventText);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $eventCreateTime = $this->event->setCreateTime(new DateTime(self::TEST_TIME));
        $createTime = $eventCreateTime->getCreateTime();

        $this->assertInstanceOf(Event::class, $eventCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame(self::TEST_TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetStartTime(): void
    {
        $eventStartTime = $this->event->setStartTime(new DateTime(self::TEST_TIME));
        $startTime = $eventStartTime->getStartTime();

        $this->assertInstanceOf(Event::class, $eventStartTime);
        $this->assertInstanceOf(DateTime::class, $startTime);
        $this->assertSame(self::TEST_TIME, $startTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetDuration(): void
    {
        $eventDuration = $this->event->setDuration(self::TEST_DURATION);
        $duration = $eventDuration->getDuration();

        $this->assertInstanceOf(Event::class, $eventDuration);
        $this->assertIsInt($duration);
        $this->assertSame(self::TEST_DURATION, $duration);
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
