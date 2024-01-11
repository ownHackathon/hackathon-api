<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Topic;
use App\Table\TopicPoolTable;
use Test\Unit\Mock\TestConstants;

/**
 * @property TopicPoolTable $table
 */
class TopicPoolTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        self::assertSame('TopicPool', $this->table->getTableName());
    }

    public function testCanInsertTopic(): void
    {
        $topic = new Topic();

        $insertTopic = $this->table->insert($topic);

        self::assertInstanceOf(TopicPoolTable::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $topic = new Topic();

        $updateTopic = $this->table->updateEventId($topic);

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
