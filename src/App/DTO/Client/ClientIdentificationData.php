<?php declare(strict_types=1);

namespace App\DTO\Client;

readonly class ClientIdentificationData
{
    public function __construct(
        public string $ident,
        public string $userAgent
    ) {
    }

    public static function create(?string $ident, string $userAgent): self
    {
        $data['ident'] = $ident ?: 'unsecure';
        $data['userAgent'] = $userAgent;

        return new self(...$data);
    }
}
