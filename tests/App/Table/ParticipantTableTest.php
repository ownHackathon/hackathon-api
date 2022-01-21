<?php declare(strict_types=1);

namespace App\Table;

use App\Model\Participant;

/**
 * @property ParticipantTable $table
 */
class ParticipantTableTest extends AbstractTableTest
{
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
            ->willReturn('');

        $insertParticipant = $this->table->insert($participant);

        $this->assertInstanceOf(ParticipantTable::class, $insertParticipant);
    }

    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $project = $this->table->findById(1);

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
        $this->configureSelectWithOneWhere('userId', 1);

        $participant = $this->table->findByUserId(1);

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

        $participant = $this->table->findByUserIdAndEventId(1, 1);

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

        $participant = $this->table->findActiveParticipantByEvent(1);

        $this->assertSame($this->fetchAllResult, $participant);
    }
}
