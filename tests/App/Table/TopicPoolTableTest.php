<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use App\Test\Mock\TestConstants;

/**
 * @property TopicPoolTable $table
 */
class TopicPoolTableTest extends AbstractTableTest
{
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
        $topic = $this->table->findById(TestConstants::TOPIC_POOL_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindByUuId(): void
    {
        $topic = $this->table->findByUuId(TestConstants::TOPIC_UUID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAll(): void
    {
        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByEventId(): void
    {
        $topic = $this->table->findByEventId(TestConstants::EVENT_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $topic = $this->table->findAvailable();

        $this->assertSame($this->fetchAllResult, $topic);
    }

    public function testCanFindByTopic(): void
    {
        $topic = $this->table->findByTopic(TestConstants::TOPIC_TITLE);

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
