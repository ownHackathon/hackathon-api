<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\UserRole;
use App\Trait\CloneReadonlyClassWith;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final readonly class User
{
    use CloneReadonlyClassWith;

    /** ToDo remove then phpcs the bug fixed */
    // phpcs:ignore Generic.NamingConventions.UpperCaseConstantName
    final public const string AUTHENTICATED_USER = 'authenticatedUser';

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public UserRole $role,
        public string $name,
        public string $password,
        public string $email,
        public DateTimeImmutable $registrationAt,
        public DateTimeImmutable $lastActionAt,
    ) {
    }
}
