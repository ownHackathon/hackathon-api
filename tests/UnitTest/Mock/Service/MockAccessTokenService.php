<?php declare(strict_types=1);

namespace UnitTest\Mock\Service;

use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Identity\Infrastructure\Service\Token\AccessTokenService;
use Firebase\JWT\JWT;
use Ramsey\Uuid\UuidInterface;

use function time;

readonly class MockAccessTokenService extends AccessTokenService
{
    public function __construct()
    {
        $config = new JwtTokenConfig('localhost', 'localhost', 60 * 15, 'HS512', 'testing');

        parent::__construct($config);
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
