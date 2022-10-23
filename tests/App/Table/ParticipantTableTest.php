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

    public function testCanInsertParticipant(): void
    {
        $participant = new Participant();
        $values = [
            'userId' => $participant->getUserId(),
            'eventId' => $participant->getEventId(),
            'approved' => $participant->isApproved(),
        ];

        $insert = $this->createInsert($values);

        $insert->expects($this->once())
            ->method('execute')
            ->willReturn(1);

        $insertParticipant = $this->table->insert($participant);

        $this->assertSame(1, $insertParticipant);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', self::TEST_PARTICIPANT_ID);

        $project = $this->table->findById(self::TEST_PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByUserId(): void
    {
        $this->configureSelectWithOneWhere('userId', self::TEST_USER_ID);

        $participant = $this->table->findByUserId(self::TEST_USER_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindByUserIdAndEventId(): void
    {
        $select = $this->createSelect();

        $select->expects($this->exactly(2))
            ->method('where')
            ->withConsecutive(
                ['userId', 1],
                ['eventId', 1],
            )
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetch')
            ->willReturn($this->fetchResult);

        $participant = $this->table->findByUserIdAndEventId(self::TEST_USER_ID, self::TEST_EVENT_ID);

        $this->assertSame($this->fetchResult, $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $select = $this->createSelect();

        $select->expects($this->exactly(3))
            ->method('where')
            ->withConsecutive(
                ['eventId', 1],
                ['approved', 1],
                ['disqualified', 0]
            )
            ->willReturnSelf();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $participant = $this->table->findActiveParticipantByEvent(self::TEST_EVENT_ID);

        $this->assertSame($this->fetchAllResult, $participant);
    }
}
