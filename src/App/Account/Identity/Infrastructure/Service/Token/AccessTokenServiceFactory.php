<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use ownHackathon\App\Account\Identity\DTO\Token\JwtTokenConfig;
use Psr\Container\ContainerInterface;

readonly final class AccessTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): AccessTokenService
    {
        /** @var array{jwt_token: array{access: array{iss: string, aud: string, duration: int, algorithmus: string, key: string}}} $config */
        $config = $container->get('config');

        $jwtTokenConfig = JwtTokenConfig::createFromArray($config['jwt_token']['access']);

        return new AccessTokenService($jwtTokenConfig);
    }
}
