<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Provider;

use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;

readonly final class EmailHashSaltProvider implements EmailHashSaltProviderInterface
{
    public function __construct(
        private string $salt,
    ) {
    }

    public function salt(): string
    {
        return $this->salt;
    }
}
