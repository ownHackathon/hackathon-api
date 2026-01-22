<?php declare(strict_types=1);

namespace Core\Entity\Workspace;

interface WorkspaceInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public string $name { get; }

    public function with(mixed ...$properties): self;
}
