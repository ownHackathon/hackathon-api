<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Model\Participant;
use App\Service\ParticipantService;
use App\Table\ParticipantTable;

class ParticipantServiceTest extends AbstractServiceTest
{
    public function testCanNotCreateParticipant(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findByUserIdAndEventId')
            ->with(1, 1)
            ->willReturn($this->fetchResult);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = new Participant();
        $participant->setId(1)
            ->setUserId(1)
            ->setEventId(1);

        $participant = $service->create($participant);

        $this->assertSame(false, $participant);
    }

    public function testCanCreateParticipant(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findByUserIdAndEventId')
            ->with(1, 1)
            ->willReturn(false);

        $table->expects($this->once())
            ->method('insert')
            ->willReturn(1);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = new Participant();
        $participant->setId(1)
            ->setUserId(1)
            ->setEventId(1);

        $participant = $service->create($participant);

        $this->assertSame(true, $participant);
    }

    public function testCanRemoveParticipant(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('remove')
            ->willReturn(true);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = new Participant();
        $participant->setId(1)
            ->setUserId(1)
            ->setEventId(1);

        $response = $service->remove($participant);

        $this->assertSame(true, $response);
    }

    public function testCanNotRemoveParticipant(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('remove')
            ->willReturn(false);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = new Participant();
        $participant->setId(1)
            ->setUserId(1)
            ->setEventId(1);

        $response = $service->remove($participant);

        $this->assertSame(false, $response);
    }

    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new ParticipantService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

    public function testCanFindById(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = $service->findById(1);

        $this->assertInstanceOf(Participant::class, $participant);
    }

    public function testCanFindByUserId(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findByUserId')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = $service->findByUserId(1);

        $this->assertInstanceOf(Participant::class, $participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $table = $this->createMock(ParticipantTable::class);

        $table->expects($this->once())
            ->method('findActiveParticipantByEvent')
            ->with(1)
            ->willReturn($this->fetchAllResult);

        $service = new ParticipantService($table, $this->hydrator);

        $participant = $service->findActiveParticipantByEvent(1);

        $this->assertIsArray($participant);
        $this->assertInstanceOf(Participant::class, $participant[0]);
    }
}
