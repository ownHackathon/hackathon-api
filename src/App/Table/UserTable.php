<?php declare(strict_types=1);

namespace App\Table;

use App\Entity\User;
use DateTime;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use LogicException;

use function boolval;
use function intval;
use function sprintf;

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

        return intval($lastInsertId);
    }

    public function update(User $user): bool
    {
        $values = [
            'uuid' => $user->getUuid(),
            'roleId' => $user->getRoleId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'registrationTime' => $user->getRegistrationTime()->format('Y-m-d H:i:s'),
            'lastAction' => $user->getLastAction()->format('Y-m-d H:i:s'),
            'active' => $user->isActive(),
            'token' => $user->getToken(),
        ];

        $affectedRowCount = $this->query->update($this->table, $values, $user->getId())->execute();

        if (!$affectedRowCount) {
            throw new InvalidArgumentException('User data could not be modified');
        }

        return boolval($affectedRowCount);
    }

    public function updateLastUserActionTime(int $id, DateTime $actionTime): self
    {
        $result = $this->query->update($this->table)
            ->set(['lastAction' => $actionTime->format('Y-m-d H:i:s')])
            ->where('id', $id)
            ->execute();

        if (!$result) {
            throw new LogicException(
                sprintf('Error updating the last activity of user id %d', $id),
                HTTP::STATUS_INTERNAL_SERVER_ERROR
            );
        }

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

    public function findByToken(string $token): bool|array
    {
        return $this->query->from($this->table)
            ->where('token', $token)
            ->fetch();
    }
}
