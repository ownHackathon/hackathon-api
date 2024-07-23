<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\EventStatus;
use Core\Trait\CloneReadonlyClassWith;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

final readonly class Event
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public int $userId,
        public string $title,
        public string $description,
        public string $eventText,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $startedAt,
        public int $duration,
        public EventStatus $status,
        public bool $ratingCompleted,
    ) {
    }
}
