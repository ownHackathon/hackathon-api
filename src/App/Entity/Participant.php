<?php declare(strict_types=1);

namespace App\Entity;

use App\System\Trait\CloneReadonlyClassWith;
use DateTimeImmutable;

final readonly class Participant
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public int $userId,
        public int $eventId,
        public DateTimeImmutable $requestedAt,
        public bool $subscribed,
        public bool $disqualified,
    ) {
    }
}
