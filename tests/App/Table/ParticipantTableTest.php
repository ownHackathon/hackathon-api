<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Model\Participant;
use App\Table\ParticipantTable;
use App\Test\Mock\TestConstants;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTableTest
{
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

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant();

        $removeParticipant = $this->table->remove($participant);

        $this->assertSame(1, $removeParticipant);
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(TestConstants::PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->table->findByUserId(TestConstants::USER_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindByUserIdAndEventId(): void
    {
        $participant = $this->table->findByUserIdAndEventId(TestConstants::USER_ID, TestConstants::EVENT_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $participant = $this->table->findActiveParticipantByEvent(TestConstants::EVENT_ID);

        $this->assertSame($this->fetchAllResult, $participant);
    }
}
