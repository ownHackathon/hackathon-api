<?php declare(strict_types=1);

namespace Test\Unit\App\Model;

use App\Entity\Event;
use App\Enum\DateTimeFormat;
use App\Enum\EventStatus;
use DateTime;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

class EventTest extends TestCase
{
    private Event $event;

    protected function setUp(): void
    {
        $this->event = new Event();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $eventId = $this->event->setId(TestConstants::USER_ID);
        $id = $eventId->getId();

        self::assertInstanceOf(Event::class, $eventId);
        self::assertIsInt($id);
        self::assertSame(TestConstants::USER_ID, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $eventUserId = $this->event->setUserId(TestConstants::USER_ID);
        $userId = $eventUserId->getUserId();

        self::assertInstanceOf(Event::class, $eventUserId);
        self::assertIsInt($userId);
        self::assertSame(TestConstants::USER_ID, $userId);
    }

    public function testCanSetAndGetName(): void
    {
        $eventName = $this->event->setTitle(TestConstants::EVENT_TITLE);
        $name = $eventName->getTitle();

        self::assertInstanceOf(Event::class, $eventName);
        self::assertIsString($name);
        self::assertSame(TestConstants::EVENT_TITLE, $name);
    }

    public function testCanSetAndGetDescription(): void
    {
        $description = $this->event->getDescription();

        self::assertNull($description);

        $eventDescription = $this->event->setDescription(TestConstants::EVENT_DESCRIPTION);
        $description = $eventDescription->getDescription();

        self::assertInstanceOf(Event::class, $eventDescription);
        self::assertIsString($description);
        self::assertSame(TestConstants::EVENT_DESCRIPTION, $description);
    }

    public function testCanSetAndGetEventText(): void
    {
        $eventEventText = $this->event->setEventText(TestConstants::EVENT_TEXT);
        $eventText = $eventEventText->getEventText();

        self::assertInstanceOf(Event::class, $eventEventText);
        self::assertIsString($eventText);
        self::assertSame(TestConstants::EVENT_TEXT, $eventText);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $eventCreateTime = $this->event->setCreateTime(new DateTime(TestConstants::TIME));
        $createTime = $eventCreateTime->getCreateTime();

        self::assertInstanceOf(Event::class, $eventCreateTime);
        self::assertInstanceOf(DateTime::class, $createTime);
        self::assertSame(TestConstants::TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetStartTime(): void
    {
        $eventStartTime = $this->event->setStartTime(new DateTime(TestConstants::TIME));
        $startTime = $eventStartTime->getStartTime();

        self::assertInstanceOf(Event::class, $eventStartTime);
        self::assertInstanceOf(DateTime::class, $startTime);
        self::assertSame(TestConstants::TIME, $startTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetDuration(): void
    {
        $eventDuration = $this->event->setDuration(TestConstants::EVENT_DURATION);
        $duration = $eventDuration->getDuration();

        self::assertInstanceOf(Event::class, $eventDuration);
        self::assertIsInt($duration);
        self::assertSame(TestConstants::EVENT_DURATION, $duration);
    }

    public function testCanSetAndGetActive(): void
    {
        $eventActive = $this->event->setStatus(EventStatus::RUNNING);
        $active = $eventActive->getStatus();

        self::assertInstanceOf(Event::class, $eventActive);
        self::assertInstanceOf(EventStatus::class, $active);
        self::assertSame(EventStatus::RUNNING, $active);
    }

    public function testCanSetAndGetRatingComplete(): void
    {
        $eventRatingComplete = $this->event->setRatingCompleted(true);
        $ratingComplete = $eventRatingComplete->isRatingCompleted();

        self::assertInstanceOf(Event::class, $eventRatingComplete);
        self::assertIsBool($ratingComplete);
        self::assertSame(true, $ratingComplete);
    }
}
