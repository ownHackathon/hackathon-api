<?php declare(strict_types=1);

namespace ownHackathon\App\Event\Domain;

use DateTimeImmutable;
use ownHackathon\App\Event\Domain\Enum\EventStatus;
use ownHackathon\Core\Shared\Domain\Enum\Visibility;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

readonly class Event implements EventInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public UuidInterface $uuid,
        public int $workspaceId,
        public ?int $topicId,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?string $details,
        public EventStatus $status,
        public Visibility $visibility,
        public DateTimeImmutable $beginsOn,
        public DateTimeImmutable $endsOn,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
