<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;

class UserTable extends AbstractTable
{
    public function findById(int $id): ?array
    {
        $user = $this->query->from('User')
            ->where('id', $id)
            ->fetch();

        if ($user) {
            return $user;
        }

        return null;
    }

    public function findByName(string $name): ?array
    {
        $user = $this->query->from('User')
            ->where('name', $name)
            ->fetch();

        if ($user) {
            return $user;
        }

        return null;
    }

}
