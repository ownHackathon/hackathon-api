<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Participant;
use App\Service\ParticipantService;
use Test\Unit\App\Mock\Table\MockParticipantTable;

class ParticipantServiceTest extends AbstractService
{
    private ParticipantService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $table = new MockParticipantTable();
        $this->service = new ParticipantService($table, $this->hydrator);
    }

    public function testCanNotCreateParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(1)
            ->setUserId(1)
            ->setEventId(1);

        $participant = $this->service->create($participant);

        $this->assertSame(false, $participant);
    }

    public function testCanCreateParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(2)
            ->setUserId(2)
            ->setEventId(2);

        $participant = $this->service->create($participant);

        $this->assertSame(true, $participant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(1);

        $response = $this->service->remove($participant);

        $this->assertSame(true, $response);
    }

    public function testCanNotRemoveParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(2);

        $response = $this->service->remove($participant);

        $this->assertSame(false, $response);
    }

    public function testFindByIdThrowException(): void
    {
        $this->expectException('InvalidArgumentException');

        $this->service->findById(2);
    }

    public function testCanFindById(): void
    {
        $participant = $this->service->findById(1);

        $this->assertInstanceOf(Participant::class, $participant);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->service->findByUserId(1);

        $this->assertInstanceOf(Participant::class, $participant);
    }

    public function testCanNotFindByUserId(): void
    {
        $participant = $this->service->findByUserId(2);

        $this->assertNull($participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
         $participant = $this->service->findActiveParticipantByEvent(1);

        $this->assertIsArray($participant);
        $this->assertArrayHasKey(0, $participant);
        $this->assertInstanceOf(Participant::class, $participant[0]);
    }
}
