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

    public function testCanInsertTopic(): void
    {
        $topic = new Topic();
        $values = [
            'topic' => $topic->getTopic(),
            'description' => $topic->getDescription(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn('');

        $insertTopic = $this->table->insert($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $insertTopic);
    }

    public function testCanUpdateEventId(): void
    {
        $topic = new Topic();
        $values = [
            'eventId' => $topic->getEventId(),
        ];

        $update = $this->createUpdate($values);

        $update->expects($this->once())
            ->method('execute')
            ->willReturn('');

        $updateTopic = $this->table->updateEventId($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $updateTopic);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', self::TEST_TOPIC_POOL_ID);

        $topic = $this->table->findById(self::TEST_TOPIC_POOL_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $users = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $users);
    }

    public function testCanFindByEventId(): void
    {
        $this->configureSelectWithOneWhere('eventId', self::TEST_EVENT_ID);

        $topic = $this->table->findByEventId(self::TEST_EVENT_ID);

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanFindAvailable(): void
    {
        $select = $this->createSelect();

        $select->expects($this->exactly(2))
            ->method('where')
            ->withConsecutive(
                ['eventId', null],
                ['accepted', 1]
            )
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $topic = $this->table->findAvailable();

        $this->assertSame($this->fetchAllResult, $topic);
    }

    public function testCanFindByTopic(): void
    {
        $this->configureSelectWithOneWhere('topic', 'fakeTopic');

        $topic = $this->table->findByTopic('fakeTopic');

        $this->assertSame($this->fetchResult, $topic);
    }

    public function testCanGetCountTopic(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('select')
            ->with('COUNT(id) AS countTopic')
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetch')
            ->willReturn(['countTopic' => 1]);

        $topicCount = $this->table->getCountTopic();

        $this->assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicAccepted(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('select')
            ->with('COUNT(id) AS countTopic')
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('where')
            ->with('accepted', 1)
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetch')
            ->willReturn(['countTopic' => 1]);

        $topicCount = $this->table->getCountTopicAccepted();

        $this->assertSame(1, $topicCount);
    }

    public function testCanGetCountTopicSelectionAvailable(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('select')
            ->with('COUNT(id) AS countTopic')
            ->willReturnSelf();

        $select->expects($this->exactly(2))
            ->method('where')
            ->withConsecutive(
                ['accepted', 1],
                ['eventId', null]
            )
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetch')
            ->willReturn(['countTopic' => 1]);

        $topicCount = $this->table->getCountTopicSelectionAvailable();

        $this->assertSame(1, $topicCount);
    }
}
