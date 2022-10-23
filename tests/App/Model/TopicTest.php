<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Model\Topic;
use PHPUnit\Framework\TestCase;

class TopicTest extends TestCase
{
    private const TEST_ID = 1;
    private const TEST_EVENT_ID = 1;
    private const TEST_TOPIC_TEXT = 'Test Topic Text';
    private const TEST_DESCRIPTION = 'Test Description';

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

        $this->assertNull($eventId);
        $this->assertNull($description);
        $this->assertNull($accepted);
    }

    public function testCanSetAndGetId(): void
    {
        $topicId = $this->topic->setId(self::TEST_ID);
        $id = $topicId->getId();

        $this->assertInstanceOf(Topic::class, $topicId);
        $this->assertIsInt($id);
        $this->assertSame(self::TEST_ID, $id);
    }

    public function testCanSetAndGetEventId(): void
    {
        $eventId = $this->topic->setEventId(self::TEST_EVENT_ID);
        $id = $eventId->getEventId();

        $this->assertInstanceOf(Topic::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(self::TEST_EVENT_ID, $id);
    }

    public function testCanSetAndGetTopic(): void
    {
        $topicTopic = $this->topic->setTopic(self::TEST_TOPIC_TEXT);
        $topic = $topicTopic->getTopic();

        $this->assertInstanceOf(Topic::class, $topicTopic);
        $this->assertIsString($topic);
        $this->assertSame(self::TEST_TOPIC_TEXT, $topic);
    }

    public function testCanSetAndGetDescription(): void
    {
        $topicDescription = $this->topic->setDescription(self::TEST_DESCRIPTION);
        $description = $topicDescription->getDescription();

        $this->assertInstanceOf(Topic::class, $topicDescription);
        $this->assertIsString($description);
        $this->assertSame(self::TEST_DESCRIPTION, $description);
    }

    public function testCanSetAndGetAccepted(): void
    {
        $topicAccepted = $this->topic->setAccepted(true);
        $accepted = $topicAccepted->getAccepted();

        $this->assertInstanceOf(Topic::class, $topicAccepted);
        $this->assertIsBool($accepted);
        $this->assertSame(true, $accepted);
    }
}
