<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Model\Topic;
use App\Table\TopicPoolTable;

/**
 * @property TopicPoolTable $table
 */
class TopicPoolTableTest extends AbstractTableTest
{
    private const TEST_TOPIC_POOL_ID = 1;
    private const TEST_EVENT_ID = 1;

    public function testCanGetTableName(): void
    {
        $this->assertSame('TopicPool', $this->table->getTableName());
    }

    public function testCanInsertTopic(): void
    {
        $topic = new Topic();

        $insertTopic = $this->table->insert($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $topic = new Topic();

        $updateTopic = $this->table->updateEventId($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $updateTopic);
    }

    public function testCanFindById(): void
    {
        $topic = $this->table->findById(self::TEST_TOPIC_POOL_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByEventId(): void
    {
        $topic = $this->table->findByEventId(self::TEST_EVENT_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $topic = $this->table->findAvailable();

        $this->assertSame($this->fetchAllResult, $topic);
    }

    public function testCanFindByTopic(): void
    {
        $topic = $this->table->findByTopic('fakeTopic');

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanGetCountTopic(): void
    {
        $topicCount = $this->table->getCountTopic();

        $this->assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicAccepted(): void
    {
        $topicCount = $this->table->getCountTopicAccepted();

        $this->assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicSelectionAvailable(): void
    {
        $topicCount = $this->table->getCountTopicSelectionAvailable();

        $this->assertSame(1, $topicCount);
    }
}
