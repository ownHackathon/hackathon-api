<?php declare(strict_types=1);

namespace ownHackathon\Event\Domain;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Visibility;
use Exdrals\Core\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Core\Shared\Utils\Collectible;
use ownHackathon\Event\Domain\Enum\EventStatus;
use ownHackathon\Shared\Domain\Event\EventInterface;
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
