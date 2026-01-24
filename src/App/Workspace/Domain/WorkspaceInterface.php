<?php declare(strict_types=1);

namespace App\Workspace\Domain;

interface WorkspaceInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public string $name { get; }

    public string $slug { get; }

    public function with(mixed ...$properties): self;
}
