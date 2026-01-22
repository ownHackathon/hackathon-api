<?php declare(strict_types=1);

namespace App\Entity\Workspace;

use Core\Entity\Workspace\WorkspaceInterface;
use Core\Trait\CloneReadonlyClassWith;
use Core\Utils\Collectible;

readonly class Workspace implements WorkspaceInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public int $accountId,
        public string $name,
    ) {
    }
}
