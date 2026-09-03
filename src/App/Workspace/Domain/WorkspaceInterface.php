<?php declare(strict_types=1);

namespace App\Workspace\Domain;

use App\Policy\Domain\Enum\Visibility;
use App\Policy\Domain\VisibilityAwareInterface;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface WorkspaceInterface extends VisibilityAwareInterface
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
