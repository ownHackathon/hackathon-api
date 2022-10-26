<?php declare(strict_types=1);

namespace App\Test\Mock\Table;

use App\Model\User;
use App\Table\UserTable;
use App\Test\Mock\Database\MockQuery;

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
        return $uuid === 'FakeUserUuid' ? ['id' => 1] : false;
    }

    public function findByName(string $name): bool|array
    {
        return $name === 'FakeNotCreateUser' ? ['id' => 1] : false;
    }

    public function findByEMail(string $email): bool|array
    {
        return $email === 'FakeNotCreateEMail' ? ['id' => 1] : false;
    }

    public function findByToken(string $token): bool|array
    {
        return $token === 'FakeUserToken' ? ['id' => 1] : false;
    }
}
