<?php declare(strict_types=1);

namespace App\Workspace\Domain;

use App\Policy\Domain\Enum\Visibility;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class Workspace implements WorkspaceInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public UuidInterface $uuid,
        public int $accountId,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?string $details,
        public Visibility $visibility,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    #[\Override]
    public function getOwnerId(): int
    {
        return $this->accountId;
    }
}
