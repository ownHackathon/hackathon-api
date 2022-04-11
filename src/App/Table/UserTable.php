<?php declare(strict_types=1);

namespace App\Table;

use DateTime;
use Administration\Table\AbstractTable;
use App\Model\User;

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

    public function updateLastUserActionTime(int $id): self
    {
        $time = new DateTime();
        $time = $time->format('Y-m-d H:i:s');
        $this->query->update($this->table)
            ->set(['lastAction' => $time])
            ->where('id', $id)
            ->execute();

        return $this;
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
