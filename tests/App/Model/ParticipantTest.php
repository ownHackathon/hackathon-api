<?php declare(strict_types=1);

namespace App\Model;

use DateTime;
use PHPUnit\Framework\TestCase;

class ParticipantTest extends TestCase
{
    private Participant $participant;
    private string $testTime = '1000-01-01 00:00:00';

    protected function setUp(): void
    {
        $this->participant = new Participant();

        parent::setUp();
    }

    public function testCanSetAndGetId(): void
    {
        $participantId = $this->participant->setId(1);
        $id = $participantId->getId();

        $this->assertInstanceOf(Participant::class, $participantId);
        $this->assertIsInt($id);
        $this->assertSame(1, $id);
    }

    public function testCanSetAndGetUserId(): void
    {
        $participantUserId = $this->participant->setUserId(1);
        $userId = $participantUserId->getUserId();

        $this->assertInstanceOf(Participant::class, $participantUserId);
        $this->assertIsInt($userId);
        $this->assertSame(1, $userId);
    }

    public function testCanSetAndGetEventId(): void
    {
        $participantEventId = $this->participant->setEventId(1);
        $eventId = $participantEventId->getEventId();

        $this->assertInstanceOf(Participant::class, $participantEventId);
        $this->assertIsInt($eventId);
        $this->assertSame(1, $eventId);
    }

    public function testCanSetAndGetRequestTime(): void
    {
        $participantRequestTime = $this->participant->setRequestTime(new DateTime($this->testTime));
        $requestTime = $participantRequestTime->getRequestTime();

        $this->assertInstanceOf(Participant::class, $participantRequestTime);
        $this->assertInstanceOf(DateTime::class, $requestTime);
        $this->assertSame($this->testTime, $requestTime->format('Y-m-d H:i:s'));
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
