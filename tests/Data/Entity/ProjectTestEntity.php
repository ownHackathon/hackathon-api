<?php declare(strict_types=1);

namespace Test\Data\Entity;

use DateTime;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\TestConstants;

class ProjectTestEntity
{
    public static function getDefaultProjectValue(): array
    {
        return [
            'id' => TestConstants::PROJECT_ID,
            'uuid' => UuidV7::fromString(TestConstants::PROJECT_UUID),
            'participantId' => TestConstants::PARTICIPANT_ID,
            'title' => TestConstants::PROJECT_TITLE,
            'description' => TestConstants::PROJECT_DESCRIPTION,
            'createdAt' => new DateTime(TestConstants::TIME),
            'gitRepoUri' => TestConstants::PROJECT_GIT_URL,
            'demoPageUri' => TestConstants::PROJECT_DEMO_URI,
        ];
    }
}
