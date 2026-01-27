<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Identity\DTO\Token\AccessToken;
use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Shared\Trait\JwtTokenTrait;
use Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;

use function time;

readonly class AccessTokenService
{
    use JwtTokenTrait;

    public function __construct(
        protected JwtTokenConfig $config,
    ) {
    }

    public function generate(UuidInterface $uuid): AccessToken
    {
        $now = time();

        $payload = [
            'iss' => $this->config->iss,
            'aud' => $this->config->aud,
            'iat' => $now,
            'exp' => $now + $this->config->duration,
            'uuid' => $uuid->toString(),
        ];

        $token = JWT::encode($payload, $this->config->key, $this->config->algorithmus);
        return AccessToken::fromString($token);
    }
}
