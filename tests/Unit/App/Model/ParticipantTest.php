<?php declare(strict_types=1);

namespace Test\Unit\App\Model;

use App\Entity\Participant;
use App\Enum\DateTimeFormat;
use DateTime;
use PHPUnit\Framework\TestCase;
use Test\Unit\Mock\TestConstants;

class ParticipantTest extends TestCase
{
    private Participant $participant;

    protected function setUp(): void
    {
        $this->participant = new Participant();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $participantId = $this->participant->setId(TestConstants::USER_ID);
        $id = $participantId->getId();

        self::assertInstanceOf(Participant::class, $participantId);
        self::assertIsInt($id);
        self::assertSame(TestConstants::USER_ID, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $participantUserId = $this->participant->setUserId(TestConstants::USER_ID);
        $userId = $participantUserId->getUserId();

        self::assertInstanceOf(Participant::class, $participantUserId);
        self::assertIsInt($userId);
        self::assertSame(TestConstants::USER_ID, $userId);
    }

    public function testCanSetAndGetEventId(): void
    {
        $participantEventId = $this->participant->setEventId(TestConstants::EVENT_ID);
        $eventId = $participantEventId->getEventId();

        self::assertInstanceOf(Participant::class, $participantEventId);
        self::assertIsInt($eventId);
        self::assertSame(TestConstants::EVENT_ID, $eventId);
    }

    public function testCanSetAndGetRequestTime(): void
    {
        $participantRequestTime = $this->participant->setRequestTime(new DateTime(TestConstants::TIME));
        $requestTime = $participantRequestTime->getRequestTime();

        self::assertInstanceOf(Participant::class, $participantRequestTime);
        self::assertInstanceOf(DateTime::class, $requestTime);
        self::assertSame(TestConstants::TIME, $requestTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetApproved(): void
    {
        $participantApproved = $this->participant->setApproved(true);
        $approved = $participantApproved->isApproved();

        self::assertInstanceOf(Participant::class, $participantApproved);
        self::assertIsBool($approved);
        self::assertSame(true, $approved);
    }

    public function testCanSetAndGetDisqualified(): void
    {
        $participantDisqualified = $this->participant->setDisqualified(true);
        $disqualified = $participantDisqualified->isDisqualified();

        self::assertInstanceOf(Participant::class, $participantDisqualified);
        self::assertIsBool($disqualified);
        self::assertSame(true, $disqualified);
    }
}
