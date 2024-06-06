<?php declare(strict_types=1);

namespace Test\Data\Entity;

use DateTimeImmutable;
use Test\Data\TestConstants;

class ParticipantTestEntity
{
    public static function getDefaultParticipantValue(): array
    {
        return [
            'id' => TestConstants::PARTICIPANT_ID,
            'userId' => TestConstants::USER_ID,
            'eventId' => TestConstants::EVENT_ID,
            'requestedAt' => new DateTimeImmutable(TestConstants::TIME),
            'subscribed' => true,
            'disqualified' => false,
        ];
    }
}
