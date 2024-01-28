<?php declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Enum\UserRole;
use App\Exception\DuplicateEntryException;
use App\Hydrator\ReflectionHydrator;
use App\Repository\UserRepository;
use DateTime;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

use function password_hash;
use function sprintf;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
        private readonly ReflectionHydrator $hydrator,
        private readonly UuidInterface $uuid,
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
            return throw new DuplicateEntryException('User', 1);
        }

        $hashedPassword = password_hash($user->password, PASSWORD_BCRYPT);

        $user = $user->with([
            'password' => $hashedPassword,
            'roleId' => $role->value,
            'uuid' => $this->uuid->getHex()->toString(),
        ]);

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
        $user = $this->repository->findById($id);

        if ($user === []) {
            throw new InvalidArgumentException(
                sprintf('Could not find user with id %d', $id),
                HTTP::STATUS_NOT_FOUND
            );
        }

        return $this->hydrator->hydrate($user, new User(new DateTime()));
    }

    public function findByUuid(string $uuid): User|null
    {
        $user = $this->repository->findByUuid($uuid);

        return $user ? $this->hydrator->hydrate($user, new User(new DateTime())) : null;
    }

    public function findByName(string $name): ?User
    {
        $user = $this->repository->findByName($name);

        return $this->hydrator->hydrate($user, new User(new DateTime()));
    }

    public function findByEMail(string $email): ?User
    {
        $user = $this->repository->findByEMail($email);

        return $this->hydrator->hydrate($user, new User(new DateTime()));
    }

    public function findByToken(string $token): ?User
    {
        $user = $this->repository->findByToken($token);

        return $this->hydrator->hydrate($user, new User(new DateTime()));
    }
}
