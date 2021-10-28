<?php declare(strict_types=1);

namespace Authentication\Service;

use Firebase\JWT\JWT;

trait JwtTokenGeneratorTrait
{
    private function generateToken(int $userId, string $userName, string $tokenSecret, int $timeout, string $alg = 'HS512'): string
    {
        $now = time();

        return JWT::encode(
            [
                'iat' => $now,
                'exp' => $now + $timeout,

                'id' => $userId,
                'name' => $userName,
            ],
            $tokenSecret,
            $alg
        );
    }
}