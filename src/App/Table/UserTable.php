<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\User;
use DateTime;
use InvalidArgumentException;

class UserTable extends AbstractTable
{
    public function insert(User $user): int
    {
        $values = [
            'roleId' => $user->getRoleId(),
            'uuid' => $user->getUuid(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
        ];

        $lastInsertId = $this->query->insertInto($this->table, $values)->execute();

        if (!$lastInsertId) {
            return throw new InvalidArgumentException('An equivalent data set already exists');
        }

        return (int)$lastInsertId;
    }

    public function update(User $user): int
    {
        $values = [
            'uuid' => $user->getUuid(),
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'registrationTime' => $user->getRegistrationTime()->format('Y-m-d H:i:s'),
            'lastAction' => $user->getLastAction()?->format('Y-m-d H:i:s'),
            'active' => $user->isActive(),
            'token' => $user->getToken(),
        ];

        $affectedRowCount = $this->query->update($this->table, $values, $user->getId())->execute();

        if (!$affectedRowCount) {
            throw new InvalidArgumentException('User data could not be modified');
        }

        return (int)$affectedRowCount;
    }

    public function updateLastUserActionTime(int $id, DateTime $actionTime): self
    {
        $result = $this->query->update($this->table)
            ->set(['lastAction' => $actionTime->format('Y-m-d H:i:s')])
            ->where('id', $id)
            ->execute();

        if (!$result) {
            throw new InvalidArgumentException('User data could not be modified');
        }

        return $this;
    }

    public function findByUuid(string $uuid): array
    {
        $result = $this->query->from($this->table)
            ->where('uuid', $uuid)
            ->fetch();

        return $result ?: [];
    }

    public function findByName(string $name): array
    {
        $result = $this->query->from($this->table)
            ->where('name', $name)
            ->fetch();

        return $result ?: [];
    }

    public function findByEMail(string $email): array
    {
        $result = $this->query->from($this->table)
            ->where('email', $email)
            ->fetch();

        return $result ?: [];
    }

    public function findByToken(string $token): array
    {
        $result = $this->query->from($this->table)
            ->where('token', $token)
            ->fetch();

        return $result ?: [];
    }
}
