<?php declare(strict_types=1);

namespace Test\Data\Entity;

use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\TestConstants;

class TopicTestEntity
{
    public static function getDefaultTopicValue(): array
    {
        return [
            'id' => TestConstants::TOPIC_ID,
            'uuid' => UuidV7::fromString(TestConstants::TOPIC_UUID),
            'eventId' => TestConstants::EVENT_ID,
            'topic' => TestConstants::TOPIC_TITLE,
            'description' => TestConstants::TOPIC_DESCRIPTION,
            'accepted' => true,
        ];
    }
}
