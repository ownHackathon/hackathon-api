<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Model\Participant;
use App\Table\ParticipantTable;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTableTest
{
    private const TEST_PARTICIPANT_ID = 1;
    private const TEST_USER_ID = 1;
    private const TEST_EVENT_ID = 1;

    public function testCanGetTableName(): void
    {
        $this->assertSame('Participant', $this->table->getTableName());
    }

    public function testCanInsertParticipant(): void
    {
        $participant = new Participant();

        $insertParticipant = $this->table->insert($participant);

        $this->assertSame(1, $insertParticipant);
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(self::TEST_PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->table->findByUserId(self::TEST_USER_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindByUserIdAndEventId(): void
    {
        $participant = $this->table->findByUserIdAndEventId(self::TEST_USER_ID, self::TEST_EVENT_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $participant = $this->table->findActiveParticipantByEvent(self::TEST_EVENT_ID);

        $this->assertSame($this->fetchAllResult, $participant);
    }
}
