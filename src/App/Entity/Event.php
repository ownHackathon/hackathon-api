<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\EventStatus;
use App\System\Trait\CloneReadonlyClassWith;
use DateTime;
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
        public DateTime $createdAt,
        public DateTime $startedAt,
        public int $duration,
        public EventStatus $status,
        public bool $ratingCompleted,
    ) {
    }
}
