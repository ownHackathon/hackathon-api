<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Participant;
use Envms\FluentPDO\Queries\Select;
use Envms\FluentPDO\Query;
use PHPUnit\Framework\TestCase;

class ParticipantTableTest extends TestCase
{
    private ParticipantTable $table;

    protected function setUp(): void
    {
        $query = $this->createMock(Query::class);

        $select = $this->getMockBuilder(Select::class)->setConstructorArgs([$query, 'TestTable'])->getMock();

        $query->method('from')->willReturn($select);

        $select->method('where')->willReturnSelf();
        $select->method('fetch')->willReturn([]);
        $select->method('fetchAll')->willReturn([]);

        $this->table = new ParticipantTable($query);

        parent::setUp();
    }

    public function testCanInsert()
    {
        $participant = new Participant();

        $insertParticipant = $this->table->insert($participant);

        $this->assertInstanceOf(ParticipantTable::class, $insertParticipant);
    }

    public function testCanFindByUserId()
    {
        $participant = $this->table->findByUserId(1);

        $this->assertIsArray($participant);
    }

    public function testCanFindByUserIdAndEventId()
    {
        $participant = $this->table->findByUserIdAndEventId(1, 1);

        $this->assertIsArray($participant);
    }

    public function testCanFindActiveParticipantByEvent()
    {
        $participant = $this->table->findActiveParticipantByEvent(1);

        $this->assertIsArray($participant);
    }
}
