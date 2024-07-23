<?php declare(strict_types=1);

namespace App\Entity;

use App\Trait\CloneReadonlyClassWith;
use Ramsey\Uuid\UuidInterface;

final readonly class Role
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public string $name,
        public string $description
    ) {
    }
}
