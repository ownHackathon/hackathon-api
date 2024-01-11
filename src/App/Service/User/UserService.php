<?php declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Enum\UserRole;
use App\Hydrator\ReflectionHydrator;
use App\Table\UserTable;
use DateTime;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

use function password_hash;
use function sprintf;

class UserService
{
    public function __construct(
        private readonly UserTable $table,
        private readonly ReflectionHydrator $hydrator,
        private readonly UuidInterface $uuid,
    ) {
    }

    public function updateLastUserActionTime(User $user): User
    {
        $user->setLastAction(new DateTime());

        $this->table->updateLastUserActionTime($user->getId(), $user->getLastAction());

        return $user;
    }

    public function create(User $user, UserRole $role = UserRole::USER): int
    {
        if ($this->isEmailExist($user->getEmail())) {
            return throw new InvalidArgumentException(sprintf('E-Mail %s already exists', $user->getEmail()));
        }

        $hashedPassword = password_hash($user->getPassword(), PASSWORD_BCRYPT);

        $user->setPassword($hashedPassword);
        $user->setRoleId($role->value);
        $user->setUuid($this->uuid->getHex()->toString());

        return $this->table->insert($user);
    }

    public function update(User $user): bool
    {
        return (bool)$this->table->update($user);
    }

    // @phpstan-ignore-next-line
    private function isUserExist(string $userName): bool
    {
        $user = $this->findByName($userName);

        return $user instanceof User;
    }

    private function isEmailExist(?string $email): bool
    {
        $isUser = null;

        if (null !== $email) {
            $isUser = $this->findByEMail($email);
        }

        return ($isUser instanceof User);
    }

    public function findById(int $id): User
    {
        $user = $this->table->findById($id);

        if ($user === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find user with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByUuid(string $uuid): User|null
    {
        $user = $this->table->findByUuid($uuid);

        return $user ? $this->hydrator->hydrate($user, new User()) : null;
    }

    public function findByName(string $name): ?User
    {
        $user = $this->table->findByName($name);

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByEMail(string $email): ?User
    {
        $user = $this->table->findByEMail($email);

        return $this->hydrator->hydrate($user, new User());
    }

    public function findByToken(string $token): ?User
    {
        $user = $this->table->findByToken($token);

        return $this->hydrator->hydrate($user, new User());
    }
}
