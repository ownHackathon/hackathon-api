<?php declare(strict_types=1);

namespace App\Entity;

use App\System\Trait\CloneReadonlyClassWith;
use Ramsey\Uuid\UuidInterface;

final readonly class Topic
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public int $eventId,
        public string $topic,
        public string $description,
        public ?bool $accepted,
    ) {
    }
}
