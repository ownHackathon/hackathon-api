<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Participant;
use App\Service\Participant\ParticipantService;
use InvalidArgumentException;
use Test\Data\Entity\ParticipantTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Core\Service\AbstractService;
use Test\Unit\Mock\Table\MockParticipantTable;

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
        $participant = new Participant(...ParticipantTestEntity::getDefaultParticipantValue());
        $participant = $participant->with(
            id: TestConstants::PARTICIPANT_ID,
            userId: TestConstants::USER_ID,
            eventId: TestConstants::EVENT_ID,
        );

        $participant = $this->service->create($participant);

        self::assertSame(false, $participant);
    }

    public function testCanCreateParticipant(): void
    {
        $participant = new Participant(...ParticipantTestEntity::getDefaultParticipantValue());
        $participant = $participant->with(
            id: TestConstants::PARTICIPANT_ID_UNUSED,
            userId: TestConstants::USER_ID_UNUSED,
            eventId: TestConstants::EVENT_ID_UNUSED,
        );

        $participant = $this->service->create($participant);

        self::assertSame(true, $participant);
    }

    public function testCanRemoveParticipant(): void
    {
        $participant = new Participant(...ParticipantTestEntity::getDefaultParticipantValue());
        $participant = $participant->with(id: TestConstants::PARTICIPANT_ID);

        $response = $this->service->remove($participant);

        self::assertSame(true, $response);
    }

    public function testCanNotRemoveParticipant(): void
    {
        $participant = new Participant(...ParticipantTestEntity::getDefaultParticipantValue());
        $participant = $participant->with(id: TestConstants::PARTICIPANT_ID_UNUSED);

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
