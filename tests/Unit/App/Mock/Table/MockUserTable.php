<?php declare(strict_types=1);

namespace Test\Unit\App\Mock\Table;

use App\Entity\User;
use App\Table\UserTable;
use InvalidArgumentException;
use Test\Unit\App\Mock\Database\MockQuery;
use Test\Unit\App\Mock\TestConstants;

class MockUserTable extends UserTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function update(User $user): int
    {
        if ($user->getId() !== TestConstants::USER_ID) {
            throw new InvalidArgumentException();
        }

        return 1;
    }

    public function findById(int $id): array
    {
        return $id === TestConstants::USER_ID ? ['id' => $id] : [];
    }

    public function findByUuid(string $uuid): array
    {
        return $uuid === TestConstants::USER_UUID ? ['id' => TestConstants::USER_ID] : [];
    }

    public function findByName(string $name): array
    {
        return $name === TestConstants::USER_NAME ? ['id' => TestConstants::USER_ID] : [];
    }

    public function findByEMail(string $email): array
    {
        return $email === TestConstants::USER_EMAIL ? ['id' => TestConstants::USER_ID] : [];
    }

    public function findByToken(string $token): array
    {
        return $token === TestConstants::USER_TOKEN ? ['id' => TestConstants::USER_ID] : [];
    }
}
