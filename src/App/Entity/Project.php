<?php declare(strict_types=1);

namespace App\Entity;

use App\System\Trait\CloneReadonlyClassWith;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final readonly class Project
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public int $participantId,
        public string $title,
        public string $description,
        public DateTimeImmutable $createdAt,
        public string $gitRepoUri,
        public string $demoPageUri,
    ) {
    }
}
