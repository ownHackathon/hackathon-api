<?php declare(strict_types=1);

namespace ownHackathon\Workspace\Domain;

use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

readonly class Workspace implements WorkspaceInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public int $accountId,
        public string $name,
        public string $slug,
    ) {
    }
}
