<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Domain;

use Exdrals\Core\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Core\Shared\Utils\Collectible;
use ownHackathon\Shared\Domain\Workspace\WorkspaceInterface;
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
    ) {
    }
}
