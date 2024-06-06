<?php declare(strict_types=1);

namespace Test\Data\Entity;

use App\Enum\EventStatus;
use DateTimeImmutable;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\TestConstants;

class EventTestEntity
{
    public static function getDefaultEventValue(): array
    {
        return [
            'id' => TestConstants::EVENT_ID,
            'uuid' => UuidV7::fromString(TestConstants::EVENT_UUID),
            'userId' => TestConstants::USER_ID,
            'title' => TestConstants::EVENT_TITLE,
            'description' => TestConstants::EVENT_DESCRIPTION,
            'eventText' => TestConstants::EVENT_TEXT,
            'createdAt' => new DateTimeImmutable(TestConstants::TIME),
            'startedAt' => new DateTimeImmutable(TestConstants::TIME),
            'duration' => TestConstants::EVENT_DURATION,
            'status' => EventStatus::SOON,
            'ratingCompleted' => false,
        ];
    }
}
