<?php declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Enum\UserRole;
use Core\Exception\DuplicateEntryException;
use Core\Hydrator\ReflectionHydrator;
use Core\Repository\UserRepository;
use DateTime;
use Ramsey\Uuid\UuidInterface;

use function password_hash;

readonly class UserService
{
    public function __construct(
        private UserRepository $repository,
        private ReflectionHydrator $hydrator,
        private UuidInterface $uuid,
    ) {
    }

    public function updateLastUserActionTime(User $user): User
    {
        $this->repository->updateLastUserActionTime($user->id, new DateTime());

        return $user;
    }

    public function create(User $user, UserRole $role = UserRole::USER): int
    {
        if ($this->isEmailExist($user->email)) {
            return throw new DuplicateEntryException('User', $user->uuid->getHex()->toString());
        }

        $hashedPassword = password_hash($user->password, PASSWORD_BCRYPT);

        $user = $user->with(
            password: $hashedPassword,
            role: $role,
            uuid: $this->uuid,
        );

        return $this->repository->insert($user);
    }

    public function update(User $user): bool
    {
        return (bool)$this->repository->update($user);
    }

    // @phpstan-ignore-next-line
    private function isUserExist(string $userName): bool
    {
        $user = $this->findByName($userName);

        return $user instanceof User;
    }

    private function isEmailExist(string $email): bool
    {
        $user = $this->findByEMail($email);

        return ($user instanceof User);
    }

    public function findById(int $id): ?User
    {
        $user = $this->repository->findById($id);

        return $user !== [] ? $this->hydrator->hydrate($user, User::class) : null;
    }

    public function findByUuid(string $uuid): ?User
    {
        $user = $this->repository->findByUuid($uuid);

        return $user !== [] ? $this->hydrator->hydrate($user, User::class) : null;
    }

    public function findByName(string $name): ?User
    {
        $user = $this->repository->findByName($name);

        return $user !== [] ? $this->hydrator->hydrate($user, User::class) : null;
    }

    public function findByEMail(string $email): ?User
    {
        $user = $this->repository->findByEMail($email);

        return $user !== [] ? $this->hydrator->hydrate($user, User::class) : null;
    }
}
