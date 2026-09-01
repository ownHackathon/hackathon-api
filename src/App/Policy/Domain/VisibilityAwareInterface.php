<?php declare(strict_types=1);

namespace ownHackathon\App\Policy\Domain;

use ownHackathon\App\Policy\Domain\Enum\Visibility;

interface VisibilityAwareInterface
{
    public Visibility $visibility { get; }

    public function getOwnerId(): ?int;
}
