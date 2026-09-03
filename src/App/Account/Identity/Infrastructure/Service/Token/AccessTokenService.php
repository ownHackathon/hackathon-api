<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Token;

use App\Account\Identity\DTO\Token\AccessToken;
use App\Account\Identity\DTO\Token\JwtTokenConfig;
use App\Token\Infrastructure\Token\JwtTokenTrait;
use Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;

use function time;

// phpcs:ignore SlevomatCodingStandard.Classes.RequireAbstractOrFinal.ClassNeitherAbstractNorFinal -- wird in Unit-Tests direkt gemockt
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
