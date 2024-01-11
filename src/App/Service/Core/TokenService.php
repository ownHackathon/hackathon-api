<?php declare(strict_types=1);

namespace App\Service\Core;

use function bin2hex;
use function openssl_random_pseudo_bytes;

class TokenService
{
    public function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
