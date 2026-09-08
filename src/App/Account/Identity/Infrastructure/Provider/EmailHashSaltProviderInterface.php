<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Provider;

interface EmailHashSaltProviderInterface
{
    public function salt(): string;
}
