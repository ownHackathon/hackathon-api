<?php declare(strict_types=1);

namespace Authentication\Service;

use Firebase\JWT\JWT;

trait JwtTokenGeneratorTrait
{
    private function generateToken(string $uuid, string $tokenSecret, int $timeout, string $alg = 'HS512'): string
    {
        $now = time();

        return JWT::encode(
            [
                'iat' => $now,
                'exp' => $now + $timeout,

                'uuid' => $uuid,
            ],
            $tokenSecret,
            $alg
        );
    }
}
