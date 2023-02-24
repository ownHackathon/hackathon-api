<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Entity\User;
use App\Table\UserTable;
use App\Test\Mock\Database\MockQuery;
use App\Test\Mock\TestConstants;

class MockUserTable extends UserTable
{
    public function __construct()
    {
        parent::__construct(new MockQuery());
    }

    public function update(User $user): bool
    {
        return ($user->getId() === 1);
    }

    public function findById(int $id): bool|array
    {
        return $id === 1 ? ['id' => $id] : false;
    }

    public function findByUuid(string $uuid): bool|array
    {
        return $uuid === TestConstants::USER_UUID ? ['id' => 1] : false;
    }

    public function findByName(string $name): bool|array
    {
        return $name === TestConstants::USER_NAME ? ['id' => 1] : false;
    }

    public function findByEMail(string $email): bool|array
    {
        return $email === TestConstants::USER_EMAIL ? ['id' => 1] : false;
    }

    public function findByToken(string $token): bool|array
    {
        return $token === TestConstants::USER_TOKEN ? ['id' => 1] : false;
    }
}
