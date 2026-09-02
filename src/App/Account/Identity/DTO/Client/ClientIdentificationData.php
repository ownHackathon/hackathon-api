<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Client;

readonly final class ClientIdentificationData
{
    public function __construct(
        public string $ident,
        public string $userAgent,
    ) {
    }

    public static function create(?string $ident, string $userAgent): self
    {
        $identity = $ident !== null && $ident !== '' ? $ident : 'unsecure';

        return new self($identity, $userAgent);
    }
}
