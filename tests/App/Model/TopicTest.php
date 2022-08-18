<?php declare(strict_types=1);

namespace AppTest\Model;

use App\Model\Topic;
use PHPUnit\Framework\TestCase;

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

        $this->assertNull($eventId);
        $this->assertNull($description);
        $this->assertNull($accepted);
    }

    public function testCanSetAndGetId(): void
    {
        $topicId = $this->topic->setId(1);
        $id = $topicId->getId();

        $this->assertInstanceOf(Topic::class, $topicId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetEventId(): void
    {
        $eventId = $this->topic->setEventId(1);
        $id = $eventId->getEventId();

        $this->assertInstanceOf(Topic::class, $eventId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetTopic(): void
    {
        $topicTopic = $this->topic->setTopic('test');
        $topic = $topicTopic->getTopic();

        $this->assertInstanceOf(Topic::class, $topicTopic);
        $this->assertIsString($topic);
        $this->assertSame('test', $topic);
    }

    public function testCanSetAndGetDescription(): void
    {
        $topicDescription = $this->topic->setDescription('test');
        $description = $topicDescription->getDescription();

        $this->assertInstanceOf(Topic::class, $topicDescription);
        $this->assertIsString($description);
        $this->assertSame('test', $description);
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
