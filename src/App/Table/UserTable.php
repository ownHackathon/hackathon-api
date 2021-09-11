<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;

class UserTable extends AbstractTable
{
    public function findByUserId(int $userId): ?array
    {
        $user = $this->query->from('User')
            ->where('userId', $userId)
            ->fetch();

        if ($user) {
            return $user;
        }

        return null;
    }

    public function findByUserName(string $userName): ?array
    {
        $user = $this->query->from('User')
            ->where('userName', $userName)
            ->fetch();

        if ($user) {
            return $user;
        }

        return null;
    }

}
