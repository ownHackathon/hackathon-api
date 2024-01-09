<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Participant;
use App\Table\ParticipantTable;
use Test\Unit\App\Mock\TestConstants;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        $this->assertSame('Participant', $this->table->getTableName());
    }

    public function testCanInsertParticipant(): void
    {
        $participant = new Participant();
        $participant->setUserId(TestConstants::USER_CREATE_ID);

        $insertParticipant = $this->table->insert($participant);

        $this->assertSame(1, $insertParticipant);
    }

    public function testCanNotInsertParticipant(): void
    {
        $participant = new Participant();
        $participant->setUserId(TestConstants::USER_ID);

        $insertParticipant = $this->table->insert($participant);

        $this->assertSame(false, $insertParticipant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant();

        $removeParticipant = $this->table->remove($participant);

        $this->assertSame(true, $removeParticipant);
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(TestConstants::PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $project = $this->table->findById(TestConstants::PARTICIPANT_ID_UNUSED);

        $this->assertSame([], $project);
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
