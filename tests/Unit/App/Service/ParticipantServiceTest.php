<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Participant;
use App\Service\Participant\ParticipantService;
use InvalidArgumentException;
use Test\Unit\Mock\Table\MockParticipantTable;
use Test\Unit\Mock\TestConstants;

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

        self::assertSame(false, $participant);
    }

    public function testCanCreateParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(2)
            ->setUserId(2)
            ->setEventId(2);

        $participant = $this->service->create($participant);

        self::assertSame(true, $participant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(1);

        $response = $this->service->remove($participant);

        self::assertSame(true, $response);
    }

    public function testCanNotRemoveParticipant(): void
    {
        $participant = new Participant();
        $participant->setId(2);

        $response = $this->service->remove($participant);

        self::assertSame(false, $response);
    }

    public function testFindByIdThrowException(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->service->findById(TestConstants::PARTICIPANT_ID_THROW_EXCEPTION);
    }

    public function testCanFindById(): void
    {
        $participant = $this->service->findById(TestConstants::PARTICIPANT_ID);

        self::assertInstanceOf(Participant::class, $participant);
    }

    public function testCanFindByUserId(): void
    {
        $participant = $this->service->findByUserId(TestConstants::USER_ID);

        self::assertInstanceOf(Participant::class, $participant);
    }

    public function testCanNotFindByUserId(): void
    {
        $participant = $this->service->findByUserId(TestConstants::USER_ID_UNUSED);

        self::assertNull($participant);
    }

    public function testCanFindActiveParticipantByEvent(): void
    {
        $participant = $this->service->findActiveParticipantByEvent(TestConstants::EVENT_ID);

        self::assertIsArray($participant);
        self::assertArrayHasKey(0, $participant);
        self::assertInstanceOf(Participant::class, $participant[0]);
    }
}
