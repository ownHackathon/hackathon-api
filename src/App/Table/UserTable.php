<?php declare(strict_types=1);

namespace App\Table;

use App\Model\User;
use DateTime;

class UserTable extends AbstractTable
{
    public function insert(User $user): int|bool
    {
        $values = [
            'roleId' => $user->getRoleId(),
            'uuid' => $user->getUuid(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        return (int)$this->query->insertInto($this->table, $values)->execute();
    }

    public function updateLastUserActionTime(int $id, DateTime $actionTime): self
    {
        $this->query->update($this->table)
            ->set(['lastAction' => $actionTime->format('Y-m-d H:i:s')])
            ->where('id', $id)
            ->execute();

        return $this;
    }

    public function findByUuid(string $uuid): bool|array
    {
        return $this->query->from($this->table)
            ->where('uuid', $uuid)
            ->fetch();
    }

    public function findByName(string $name): bool|array
    {
        return $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();
    }

    public function findByEMail(string $email): bool|array
    {
        return $this->query->from($this->table)
            ->where('email', $email)
            ->fetch();
    }
}
