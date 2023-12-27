<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Enum\EventStatus;
use App\Entity\Event;
use App\Test\Mock\TestConstants;
use DateTime;
use PHPUnit\Framework\TestCase;

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

        $this->assertInstanceOf(Event::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(TestConstants::USER_ID, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $eventUserId = $this->event->setUserId(TestConstants::USER_ID);
        $userId = $eventUserId->getUserId();

        $this->assertInstanceOf(Event::class, $eventUserId);
        $this->assertIsInt($userId);
        $this->assertSame(TestConstants::USER_ID, $userId);
    }

    public function testCanSetAndGetName(): void
    {
        $eventName = $this->event->setTitle(TestConstants::EVENT_TITLE);
        $name = $eventName->getTitle();

        $this->assertInstanceOf(Event::class, $eventName);
        $this->assertIsString($name);
        $this->assertSame(TestConstants::EVENT_TITLE, $name);
    }

    public function testCanSetAndGetDescription(): void
    {
        $description = $this->event->getDescription();

        $this->assertNull($description);

        $eventDescription = $this->event->setDescription(TestConstants::EVENT_DESCRIPTION);
        $description = $eventDescription->getDescription();

        $this->assertInstanceOf(Event::class, $eventDescription);
        $this->assertIsString($description);
        $this->assertSame(TestConstants::EVENT_DESCRIPTION, $description);
    }

    public function testCanSetAndGetEventText(): void
    {
        $eventEventText = $this->event->setEventText(TestConstants::EVENT_TEXT);
        $eventText = $eventEventText->getEventText();

        $this->assertInstanceOf(Event::class, $eventEventText);
        $this->assertIsString($eventText);
        $this->assertSame(TestConstants::EVENT_TEXT, $eventText);
    }

    public function testCanSetAndGetCreateTime(): void
    {
        $eventCreateTime = $this->event->setCreateTime(new DateTime(TestConstants::TIME));
        $createTime = $eventCreateTime->getCreateTime();

        $this->assertInstanceOf(Event::class, $eventCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame(TestConstants::TIME, $createTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetStartTime(): void
    {
        $eventStartTime = $this->event->setStartTime(new DateTime(TestConstants::TIME));
        $startTime = $eventStartTime->getStartTime();

        $this->assertInstanceOf(Event::class, $eventStartTime);
        $this->assertInstanceOf(DateTime::class, $startTime);
        $this->assertSame(TestConstants::TIME, $startTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetDuration(): void
    {
        $eventDuration = $this->event->setDuration(TestConstants::EVENT_DURATION);
        $duration = $eventDuration->getDuration();

        $this->assertInstanceOf(Event::class, $eventDuration);
        $this->assertIsInt($duration);
        $this->assertSame(TestConstants::EVENT_DURATION, $duration);
    }

    public function testCanSetAndGetActive(): void
    {
        $eventActive = $this->event->setStatus(EventStatus::RUNNING->value);
        $active = $eventActive->getStatus();

        $this->assertInstanceOf(Event::class, $eventActive);
        $this->assertInstanceOf(EventStatus::class, $active);
        $this->assertSame(EventStatus::RUNNING, $active);
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
