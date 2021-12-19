<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Topic;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class TopicPoolTableTest extends TestCase
{
    private TopicPoolTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('select')->willReturnSelf();
        $select->method('fetch')->willReturn(['countTopic' => 1]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new TopicPoolTable($query);

        parent::setUp();
    }

    public function testCanInsert()
    {
        $topicPool = new Topic();

        $insertTopicPool = $this->table->insert($topicPool);

        $this->assertInstanceOf(TopicPoolTable::class, $insertTopicPool);
    }

    public function testCanUpdateEventId()
    {
        $topicPool = new Topic();

        $insertTopicPool = $this->table->updateEventId($topicPool);

        $this->assertInstanceOf(TopicPoolTable::class, $insertTopicPool);
    }

    public function testCanFindByEventId()
    {
        $topicPool = $this->table->findByEventId(1);

        $this->assertIsArray($topicPool);
    }

    public function testCanFindAvaible()
    {
        $topicPool = $this->table->findAvailable();

        $this->assertIsArray($topicPool);
    }

    public function testCanFindByTopic()
    {
        $topicPool = $this->table->findByTopic('Testtopic');

        $this->assertIsArray($topicPool);
    }

    public function testCanGetCountTopic()
    {
        $topicPool = $this->table->getCountTopic();

        $this->assertIsInt($topicPool);
    }

    public function testCanFetCountTopicAccepted()
    {
        $topicPool = $this->table->getCountTopicAccepted();

        $this->assertIsInt($topicPool);
    }

    public function testCanGetCountTopicSelectionAvailable()
    {
        $topicPool = $this->table->getCountTopicSelectionAvailable();

        $this->assertIsInt($topicPool);
    }
}
