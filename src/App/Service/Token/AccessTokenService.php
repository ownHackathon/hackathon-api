<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use Firebase\JWT\JWT;
use ownHackathon\App\DTO\Token\JwtTokenConfig;
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
            'uuid' => $uuid->getHex()->toString(),
        ];

        return JWT::encode($payload, $this->config->key, $this->config->algorithmus);
    }
}
