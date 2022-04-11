<?php declare(strict_types=1);

namespace App\Table;

use Administration\Table\AbstractTable;
use App\Model\User;
use DateTime;

class UserTable extends AbstractTable
{
    public function insert(User $user): self
    {
        $values = [
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        $this->query->insertInto($this->table, $values)->execute();

        return $this;
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
