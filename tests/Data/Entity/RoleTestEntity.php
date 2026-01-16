<?php declare(strict_types=1);

namespace Test\Data\Entity;

use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\TestConstants;

class RoleTestEntity
{
    public static function getDefaultRoleValue(): array
    {
        return [
            'id' => TestConstants::ROLE_ID,
            'uuid' => UuidV7::fromString(TestConstants::ROLE_UUID),
            'name' => TestConstants::ROLE_NAME,
            'description' => TestConstants::ROLE_DESCRIPTION,
        ];
    }
}
