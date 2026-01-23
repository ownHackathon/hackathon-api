<?php declare(strict_types=1);

namespace App\Service\Token;

use Firebase\JWT\JWT;
use App\DTO\Token\JwtTokenConfig;
use Ramsey\Uuid\UuidInterface;

use function time;

readonly class AccessTokenService
{
    use JwtTokenTrait;

    public function __construct(
        protected JwtTokenConfig $config,
    ) {
    }

    public function generate(UuidInterface $uuid): string
    {
        $now = time();

        $payload = [
            'iss' => $this->config->iss,
            'aud' => $this->config->aud,
            'iat' => $now,
            'exp' => $now + $this->config->duration,
            'uuid' => $uuid->toString(),
        ];

        return JWT::encode($payload, $this->config->key, $this->config->algorithmus);
    }
}
