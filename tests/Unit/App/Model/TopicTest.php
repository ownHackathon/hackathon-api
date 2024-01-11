<?php declare(strict_types=1);

namespace Test\Unit\App\Model;

use App\Entity\Topic;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

class TopicTest extends TestCase
{
    private Topic $topic;

    protected function setUp(): void
    {
        $this->topic = new Topic();

        parent::setUp();
    }

    public function testPropertiesIsByInitializeNull(): void
    {
        $eventId = $this->topic->getEventId();
        $description = $this->topic->getDescription();
        $accepted = $this->topic->getAccepted();

        self::assertNull($eventId);
        self::assertNull($description);
        self::assertNull($accepted);
    }

    public function testCanSetAndGetId(): void
    {
        $topicId = $this->topic->setId(TestConstants::TOPIC_ID);
        $id = $topicId->getId();

        self::assertInstanceOf(Topic::class, $topicId);
        self::assertIsInt($id);
        self::assertSame(TestConstants::TOPIC_ID, $id);
    }

    public function testCanSetAndGetUuId(): void
    {
        $topicId = $this->topic->setUuid(TestConstants::TOPIC_UUID);
        $uuid = $topicId->getUuid();

        self::assertInstanceOf(Topic::class, $topicId);
        self::assertIsString($uuid);
        self::assertSame(TestConstants::TOPIC_UUID, $uuid);
    }

    public function testCanSetAndGetEventId(): void
    {
        $eventId = $this->topic->setEventId(TestConstants::EVENT_ID);
        $id = $eventId->getEventId();

        self::assertInstanceOf(Topic::class, $eventId);
        self::assertIsInt($id);
        self::assertSame(TestConstants::EVENT_ID, $id);
    }

    public function testCanSetAndGetTopic(): void
    {
        $topicTopic = $this->topic->setTopic(TestConstants::TOPIC_TEXT);
        $topic = $topicTopic->getTopic();

        self::assertInstanceOf(Topic::class, $topicTopic);
        self::assertIsString($topic);
        self::assertSame(TestConstants::TOPIC_TEXT, $topic);
    }

    public function testCanSetAndGetDescription(): void
    {
        $topicDescription = $this->topic->setDescription(TestConstants::EVENT_DESCRIPTION);
        $description = $topicDescription->getDescription();

        self::assertInstanceOf(Topic::class, $topicDescription);
        self::assertIsString($description);
        self::assertSame(TestConstants::EVENT_DESCRIPTION, $description);
    }

    public function testCanSetAndGetAccepted(): void
    {
        $topicAccepted = $this->topic->setAccepted(true);
        $accepted = $topicAccepted->getAccepted();

        self::assertInstanceOf(Topic::class, $topicAccepted);
        self::assertIsBool($accepted);
        self::assertSame(true, $accepted);
    }
}
