<?php declare(strict_types=1);

namespace App\Account\Identity\Api;

interface EmailHashSaltProviderInterface
{
    public function salt(): string;
}
