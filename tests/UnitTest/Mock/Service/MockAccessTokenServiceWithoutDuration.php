<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Service;

use Firebase\JWT\JWT;
use ownHackathon\App\DTO\Token\JwtTokenConfig;
use ownHackathon\App\Service\Token\AccessTokenService;
use Ramsey\Uuid\UuidInterface;

use function time;

readonly class MockAccessTokenServiceWithoutDuration extends AccessTokenService
{
    public function __construct()
    {
        $config = new JwtTokenConfig('localhost', 'localhost', -10, 'HS512', 'testing');

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
            'uuid' => $uuid->getHex()->toString(),
        ];

        return JWT::encode($payload, $this->config->key, $this->config->algorithmus);
    }
}
