<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Token;

use Exdrals\Account\Identity\DTO\Token\JwtTokenConfig;
use Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;
use Shared\Trait\JwtTokenTrait;

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
