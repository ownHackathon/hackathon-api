<?php declare(strict_types=1);

namespace App\Token;

use function bin2hex;
use function openssl_random_pseudo_bytes;

readonly class TokenService
{
    public function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
