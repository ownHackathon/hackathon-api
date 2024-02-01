<?php declare(strict_types=1);

namespace Test\Data\Entity;

use App\Enum\UserRole;
use DateTime;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\TestConstants;

class UserTestEntity
{
    public static function getDefaultUserValue(): array
    {
        return [
            'id' => TestConstants::USER_ID,
            'uuid' => UuidV7::fromString(TestConstants::USER_UUID),
            'role' => UserRole::USER,
            'name' => TestConstants::USER_NAME,
            'password' => TestConstants::USER_PASSWORD,
            'email' => TestConstants::USER_EMAIL,
            'registrationAt' => new DateTime(TestConstants::TIME),
            'lastActionAt' => new DateTime(TestConstants::TIME),
        ];
    }
}
