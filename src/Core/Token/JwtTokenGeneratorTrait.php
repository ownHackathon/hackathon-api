<?php declare(strict_types=1);

namespace Core\Token;

use Firebase\JWT\JWT;

use function time;

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
