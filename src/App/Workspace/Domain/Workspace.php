<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain;

use DateTimeImmutable;
use ownHackathon\Core\SharedKernel\Domain\Enum\Visibility;
use ownHackathon\Core\SharedKernel\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\SharedKernel\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class Workspace implements WorkspaceInterface, Collectible
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
}
