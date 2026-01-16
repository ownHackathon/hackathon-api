<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use Firebase\JWT\JWT;
use ownHackathon\App\DTO\Client\ClientIdentification;
use ownHackathon\App\DTO\Token\JwtTokenConfig;

use function time;

readonly class RefreshTokenService
{
    use JwtTokenTrait;

    public function __construct(
        private JwtTokenConfig $config,
    ) {
    }

    public function generate(ClientIdentification $clientIdentification): string
    {
        $now = time();

        $payload = [
            'iss' => $this->config->iss,
            'aud' => $this->config->aud,
            'iat' => $now,
            'exp' => $now + $this->config->duration,
            'ident' => $clientIdentification->identificationHash,
        ];

        return JWT::encode($payload, $this->config->key, $this->config->algorithmus);
    }
}
