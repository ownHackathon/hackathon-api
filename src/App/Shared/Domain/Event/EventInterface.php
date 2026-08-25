<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\Event;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Visibility;
use ownHackathon\Event\Domain\Enum\EventStatus;
use Ramsey\Uuid\UuidInterface;

interface EventInterface
{
    public int $id { get; }
    public UuidInterface $uuid { get; }
    public int $workspaceId { get; }
    public ?int $topicId { get; }
    public string $name { get; }
    public string $slug { get; }
    public ?string $description { get; }
    public ?string $details { get; }
    public EventStatus $status { get; }
    public Visibility $visibility { get; }
    public DateTimeImmutable $beginsOn { get; }
    public DateTimeImmutable $endsOn { get; }
    public DateTimeImmutable $createdAt { get; }
}
