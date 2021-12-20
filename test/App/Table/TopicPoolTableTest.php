<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Topic;
use Envms\FluentPDO\Queries\Insert;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Queries\Update;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class TopicPoolTableTest extends TestCase
{
    use TableTestMockTrait;

    private Query $query;
    private Select $select;

    protected function setUp(): void
    {
        $this->query = $this->getMockBuilder(Query::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->select = $this->getMockBuilder(Select::class)
            ->setConstructorArgs([$this->query, 'TopicPool'])
            ->getMock();

        parent::setUp();
    }

    public function testCanInsertTopic()
    {
        $query = clone $this->query;
        $insert = $this->createMock(Insert::class);
        $topic = new Topic();
        $values = [
            'topic' => $topic->getTopic(),
            'description' => $topic->getDescription(),
        ];

        $query->expects($this->exactly(1))
            ->method('insertInto')
            ->with('TopicPool', $values)
            ->willReturn($insert);

        $insert->expects($this->exactly(1))
            ->method('execute')
            ->willReturn('');

        $table = new TopicPoolTable($query);

        $insertTopic = $table->insert($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $insertTopic);
    }

    public function testCanUpdateEventId()
    {
        $query = clone $this->query;
        $update = $this->createMock(Update::class);
        $topic = new Topic();
        $values = [
            'eventId' => $topic->getEventId(),
        ];

        $query->expects($this->exactly(1))
            ->method('update')
            ->with('TopicPool', $values, $topic->getId())
            ->willReturn($update);

        $update->expects($this->exactly(1))
            ->method('execute')
            ->willReturn('');

        $table = new TopicPoolTable($query);

        $updateTopic = $table->updateEventId($topic);

        $this->assertInstanceOf(TopicPoolTable::class, $updateTopic);
    }
}
