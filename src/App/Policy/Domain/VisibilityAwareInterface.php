<?php declare(strict_types=1);

namespace App\Policy\Domain;

use App\Policy\Domain\Enum\Visibility;

interface VisibilityAwareInterface
{
    public Visibility $visibility { get; }

    public function getOwnerId(): ?int;
}
