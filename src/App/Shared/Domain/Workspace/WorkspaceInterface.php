<?php declare(strict_types=1);

namespace ownHackathon\Shared\Domain\Workspace;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Visibility;
use Ramsey\Uuid\UuidInterface;

interface WorkspaceInterface
{
    public ?int $id { get; }

    public UuidInterface $uuid { get; }

    public int $accountId { get; }

    public string $name { get; }

    public string $slug { get; }

    public ?string $description { get; }

    public ?string $details { get; }

    public Visibility $visibility { get; }

    public DateTimeImmutable $createdAt { get; }

    public DateTimeImmutable $updatedAt { get; }

    public function with(mixed ...$properties): self;
}
