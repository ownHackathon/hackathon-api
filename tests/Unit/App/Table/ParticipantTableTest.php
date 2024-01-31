<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Entity\Participant;
use App\Exception\DuplicateEntryException;
use App\Table\ParticipantTable;
use DateTime;
use Test\Unit\Mock\Database\MockQueryForCanNot;
use Test\Unit\Mock\TestConstants;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTable
{
    private array $defaultParticipantValue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultParticipantValue = [
            'id' => TestConstants::PARTICIPANT_ID,
            'userId' => TestConstants::USER_ID,
            'eventId' => TestConstants::EVENT_ID,
            'requestedAt' => new DateTime(TestConstants::TIME),
            'subscribed' => true,
            'disqualified' => false,
        ];
    }

    public function testCanGetTableName(): void
    {
        self::assertSame('Participant', $this->table->getTableName());
    }

    public function testCanInsertParticipant(): void
    {
        $participant = new Participant(...$this->defaultParticipantValue);
        $participant = $participant->with(userId: TestConstants::USER_CREATE_ID);

        $insertParticipant = $this->table->insert($participant);

        self::assertSame(1, $insertParticipant);
    }

    public function testInsertParticipantThrowsException(): void
    {
        $participant = new Participant(...$this->defaultParticipantValue);
        $participant = $participant->with(userId: TestConstants::USER_ID);

        self::expectException(DuplicateEntryException::class);

        $this->table->insert($participant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant(...$this->defaultParticipantValue);

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

    public function testFindAllHasEmptyResult(): void
    {
        $table = new ParticipantTable(new MockQueryForCanNot());

        $project = $table->findAll();

        self::assertSame([], $project);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->table->findByUserId(TestConstants::USER_ID);

        self::assertSame($this->fetchResult, $participant);
    }

    public function testFindByUserIdHasEmptyResult(): void
    {
        $participant = $this->table->findByUserId(TestConstants::USER_ID_UNUSED);

        self::assertSame([], $participant);
    }

    public function testCanFindByUserIdAndEventId(): void
    {
        $participant = $this->table->findUserForAnEvent(TestConstants::USER_ID, TestConstants::EVENT_ID);

        self::assertSame($this->fetchResult, $participant);
    }

    public function testFindByUserIdAndEventIdHasEmptyResult(): void
    {
        $participant = $this->table->findUserForAnEvent(TestConstants::USER_ID_UNUSED, TestConstants::EVENT_ID_UNUSED);

        self::assertSame([], $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $participant = $this->table->findActiveParticipantsByEvent(TestConstants::EVENT_ID);

        self::assertSame($this->fetchAllResult, $participant);
    }

    public function testFindActiveParticipantByEventHasEmptyResult(): void
    {
        $table = new ParticipantTable(new MockQueryForCanNot());

        $participant = $table->findActiveParticipantsByEvent(TestConstants::EVENT_ID_UNUSED);

        self::assertSame([], $participant);
    }
}
