<?php declare(strict_types=1);

namespace Test\Unit\Mock\Table;

use App\Entity\User;
use Core\Table\UserTable;
use InvalidArgumentException;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Data\Entity\UserTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Mock\Database\MockQuery;

readonly class MockUserTable extends UserTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function update(User $user): int
    {
        if ($user->id !== TestConstants::USER_ID) {
            throw new InvalidArgumentException();
        }

        return 1;
    }

    public function findById(int $id): array
    {
        return $id === TestConstants::USER_ID ? ['id' => $id] + UserTestEntity::getDefaultUserValue() : [];
    }

    public function findByUuid(string $uuid): array
    {
        return $uuid === TestConstants::USER_UUID
            ? ['uuid' => UuidV7::fromString($uuid)] + UserTestEntity::getDefaultUserValue()
            : [];
    }

    public function findByName(string $name): array
    {
        return $name === TestConstants::USER_NAME ? ['name' => $name] + UserTestEntity::getDefaultUserValue() : [];
    }

    public function findByEMail(string $email): array
    {
        return $email === TestConstants::USER_EMAIL ? ['email' => $email] + UserTestEntity::getDefaultUserValue() : [];
    }
}
