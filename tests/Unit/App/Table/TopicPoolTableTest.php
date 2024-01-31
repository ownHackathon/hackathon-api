<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Unit\Mock\TestConstants;

/**
 * @property TopicPoolTable $table
 */
class TopicPoolTableTest extends AbstractTable
{
    private array $defaultTopicValue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultTopicValue = [
            'id' => TestConstants::TOPIC_ID,
            'uuid' => UuidV7::fromString(TestConstants::TOPIC_UUID),
            'eventId' => TestConstants::EVENT_ID,
            'topic' => TestConstants::TOPIC_TITLE,
            'description' => TestConstants::TOPIC_DESCRIPTION,
            'accepted' => true,
        ];
    }

    public function testCanGetTableName(): void
    {
        self::assertSame('TopicPool', $this->table->getTableName());
    }

    public function testCanInsertTopic(): void
    {
        $topic = new Topic(...$this->defaultTopicValue);

        $insertTopic = $this->table->insert($topic);

        self::assertInstanceOf(TopicPoolTable::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $topic = new Topic(...$this->defaultTopicValue);

        $updateTopic = $this->table->assignAnEvent($topic->id, $topic->eventId);

        self::assertInstanceOf(TopicPoolTable::class, $updateTopic);
    }

    public function testCanFindById(): void
    {
        $topic = $this->table->findById(TestConstants::TOPIC_POOL_ID);

        self::assertSame($this->fetchResult, $topic);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $topic = $this->table->findById(TestConstants::TOPIC_POOL_ID_UNUSED);

        self::assertSame([], $topic);
    }

    public function testCanFindByUuId(): void
    {
        $topic = $this->table->findByUuId(TestConstants::TOPIC_UUID);

        self::assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        self::assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByEventId(): void
    {
        $topic = $this->table->findByEventId(TestConstants::EVENT_ID);

        self::assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $topic = $this->table->findAvailable();

        self::assertSame($this->fetchAllResult, $topic);
    }

    public function testCanFindByTopic(): void
    {
        $topic = $this->table->findByTopic(TestConstants::TOPIC_TITLE);

        self::assertSame($this->fetchResult, $topic);
    }

    public function testCanGetCountTopic(): void
    {
        $topicCount = $this->table->getCountTopic();

        self::assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicAccepted(): void
    {
        $topicCount = $this->table->getCountTopicAccepted();

        self::assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicSelectionAvailable(): void
    {
        $topicCount = $this->table->getCountTopicSelectionAvailable();

        self::assertSame(1, $topicCount);
    }
}
