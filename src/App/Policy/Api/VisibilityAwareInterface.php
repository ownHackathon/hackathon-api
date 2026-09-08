<?php declare(strict_types=1);

namespace App\Policy\Api;

use App\Policy\Api\Enum\Visibility;

interface VisibilityAwareInterface
{
    public Visibility $visibility { get; }

    public function getOwnerId(): ?int;
}
