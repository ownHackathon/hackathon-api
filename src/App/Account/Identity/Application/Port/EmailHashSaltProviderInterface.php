<?php declare(strict_types=1);

namespace App\Account\Identity\Application\Port;

interface EmailHashSaltProviderInterface
{
    public function salt(): string;
}
