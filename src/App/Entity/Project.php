<?php declare(strict_types=1);

namespace App\Entity;

use App\System\Trait\CloneReadonlyClassWith;
use DateTime;
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
        public DateTime $createTime,
        public string $gitRepoUri,
        public string $demoPageUri,
    ) {
    }
}
