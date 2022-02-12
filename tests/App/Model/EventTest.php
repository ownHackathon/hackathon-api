<?php declare(strict_types=1);

namespace App\Model;

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

    public function testCanSetAndGetId()
    {
        $eventId = $this->event->setId(1);
        $id = $eventId->getId();

        $this->assertInstanceOf(Event::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
        $this->assertNull();
    }

    public function testCanSetAndGetUserId()
    {
        $eventUserId = $this->event->setUserId(1);
        $userId = $eventUserId->getUserId();

        $this->assertInstanceOf(Event::class, $eventUserId);
        $this->assertIsInt($userId);
        $this->assertSame(1, $userId);
    }

    public function testCanSetAndGetName()
    {
        $eventName = $this->event->setName('test');
        $name = $eventName->getName();

        $this->assertInstanceOf(Event::class, $eventName);
        $this->assertIsString($name);
        $this->assertSame('test', $name);
    }

    public function testCanSetAndGetDescription()
    {
        $description = $this->event->getDescription();

        $this->assertNull($description);

        $eventDescription = $this->event->setDescription('test');
        $description = $eventDescription->getDescription();

        $this->assertInstanceOf(Event::class, $eventDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
    }

    public function testCanSetAndGetEventText()
    {
        $eventEventText = $this->event->setEventText('test');
        $eventText = $eventEventText->getEventText();

        $this->assertInstanceOf(Event::class, $eventEventText);
        $this->assertIsString($eventText);
        $this->assertSame('test', $eventText);
    }

    public function testCanSetAndGetCreateTime()
    {
        $time = '1000-01-01 00:00:00';
        $eventCreateTime = $this->event->setCreateTime(new DateTime($time));
        $createTime = $eventCreateTime->getCreateTime();

        $this->assertInstanceOf(Event::class, $eventCreateTime);
        $this->assertInstanceOf(DateTime::class, $createTime);
        $this->assertSame($time, $createTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetStartTime()
    {
        $time = '1000-01-01 00:00:00';
        $eventStartTime = $this->event->setStartTime(new DateTime($time));
        $startTime = $eventStartTime->getStartTime();

        $this->assertInstanceOf(Event::class, $eventStartTime);
        $this->assertInstanceOf(DateTime::class, $startTime);
        $this->assertSame($time, $startTime->format('Y-m-d H:i:s'));
    }

    public function testCanSetAndGetDuration()
    {
        $eventDuration = $this->event->setDuration(1);
        $duration = $eventDuration->getDuration();

        $this->assertInstanceOf(Event::class, $eventDuration);
        $this->assertIsInt($duration);
        $this->assertSame(1, $duration);
    }

    public function testCanSetAndGetActive()
    {
        $eventActive = $this->event->setActive(true);
        $active = $eventActive->isActive();

        $this->assertInstanceOf(Event::class, $eventActive);
        $this->assertIsBool($active);
        $this->assertSame(true, $active);
    }

    public function testCanSetAndGetRatingComplete()
    {
        $eventRatingComplete = $this->event->setRatingCompleted(true);
        $ratingComplete = $eventRatingComplete->isRatingCompleted();

        $this->assertInstanceOf(Event::class, $eventRatingComplete);
        $this->assertIsBool($ratingComplete);
        $this->assertSame(true, $ratingComplete);
    }
}
