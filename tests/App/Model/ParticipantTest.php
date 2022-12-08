<?php declare(strict_types=1);

namespace App\Test\Model;

use App\Enum\DateTimeFormat;
use App\Model\Participant;
use App\Test\Mock\TestConstants;
use DateTime;
use PHPUnit\Framework\TestCase;

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

        $this->assertInstanceOf(Participant::class, $participantId);
        $this->assertIsInt($id);
        $this->assertSame(TestConstants::USER_ID, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $participantUserId = $this->participant->setUserId(TestConstants::USER_ID);
        $userId = $participantUserId->getUserId();

        $this->assertInstanceOf(Participant::class, $participantUserId);
        $this->assertIsInt($userId);
        $this->assertSame(TestConstants::USER_ID, $userId);
    }

    public function testCanSetAndGetEventId(): void
    {
        $participantEventId = $this->participant->setEventId(TestConstants::EVENT_ID);
        $eventId = $participantEventId->getEventId();

        $this->assertInstanceOf(Participant::class, $participantEventId);
        $this->assertIsInt($eventId);
        $this->assertSame(TestConstants::EVENT_ID, $eventId);
    }

    public function testCanSetAndGetRequestTime(): void
    {
        $participantRequestTime = $this->participant->setRequestTime(new DateTime(TestConstants::TIME));
        $requestTime = $participantRequestTime->getRequestTime();

        $this->assertInstanceOf(Participant::class, $participantRequestTime);
        $this->assertInstanceOf(DateTime::class, $requestTime);
        $this->assertSame(TestConstants::TIME, $requestTime->format(DateTimeFormat::ISO_8601->value));
    }

    public function testCanSetAndGetApproved(): void
    {
        $participantApproved = $this->participant->setApproved(true);
        $approved = $participantApproved->isApproved();

        $this->assertInstanceOf(Participant::class, $participantApproved);
        $this->assertIsBool($approved);
        $this->assertSame(true, $approved);
    }

    public function testCanSetAndGetDisqualified(): void
    {
        $participantDisqualified = $this->participant->setDisqualified(true);
        $disqualified = $participantDisqualified->isDisqualified();

        $this->assertInstanceOf(Participant::class, $participantDisqualified);
        $this->assertIsBool($disqualified);
        $this->assertSame(true, $disqualified);
    }
}
