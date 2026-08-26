<?php declare(strict_types=1);

namespace ownHackathon\App\Workspace\Domain;

use DateTimeImmutable;
use ownHackathon\Core\Shared\Domain\Enum\Visibility;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;
use ownHackathon\App\Shared\Domain\Workspace\WorkspaceInterface;
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
