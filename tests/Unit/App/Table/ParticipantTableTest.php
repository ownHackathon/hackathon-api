<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Participant;
use App\Table\ParticipantTable;
use Test\Unit\Mock\TestConstants;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTable
{
    public function testCanGetTableName(): void
    {
        self::assertSame('Participant', $this->table->getTableName());
    }

    public function testCanInsertParticipant(): void
    {
        $participant = new Participant();
        $participant->setUserId(TestConstants::USER_CREATE_ID);

        $insertParticipant = $this->table->insert($participant);

        self::assertSame(1, $insertParticipant);
    }

    public function testCanNotInsertParticipant(): void
    {
        $participant = new Participant();
        $participant->setUserId(TestConstants::USER_ID);

        $insertParticipant = $this->table->insert($participant);

        self::assertSame(false, $insertParticipant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant();

        $removeParticipant = $this->table->remove($participant);

        self::assertSame(true, $removeParticipant);
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(TestConstants::PARTICIPANT_ID);

        self::assertSame($this->fetchResult, $project);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $project = $this->table->findById(TestConstants::PARTICIPANT_ID_UNUSED);

        self::assertSame([], $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        self::assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->table->findByUserId(TestConstants::USER_ID);

        self::assertSame($this->fetchResult, $participant);
    }

    public function testCanFindByUserIdAndEventId(): void
    {
        $participant = $this->table->findByUserIdAndEventId(TestConstants::USER_ID, TestConstants::EVENT_ID);

        self::assertSame($this->fetchResult, $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $participant = $this->table->findActiveParticipantByEvent(TestConstants::EVENT_ID);

        self::assertSame($this->fetchAllResult, $participant);
    }
}
