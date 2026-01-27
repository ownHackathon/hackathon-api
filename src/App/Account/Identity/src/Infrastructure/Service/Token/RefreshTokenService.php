<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Identity\DTO\Client\ClientIdentification;
use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Identity\DTO\Token\RefreshToken;
use Firebase\JWT\JWT;
use Exdrals\Shared\Trait\JwtTokenTrait;

use function time;

readonly class RefreshTokenService
{
    use JwtTokenTrait;

    public function __construct(
        private JwtTokenConfig $config,
    ) {
    }

    public function generate(ClientIdentification $clientIdentification): RefreshToken
    {
        $now = time();

        $payload = [
            'iss' => $this->config->iss,
            'aud' => $this->config->aud,
            'iat' => $now,
            'exp' => $now + $this->config->duration,
            'ident' => $clientIdentification->identificationHash,
        ];

        $token = JWT::encode($payload, $this->config->key, $this->config->algorithmus);

        return RefreshToken::fromString($token);
    }
}
