<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use ownHackathon\App\DTO\Token\JwtTokenConfig;
use Psr\Container\ContainerInterface;

readonly class AccessTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): AccessTokenService
    {
        $jwtTokenConfig = $container->get('config')['jwt_token']['access'];
        $jwtTokenConfig = JwtTokenConfig::createFromArray($jwtTokenConfig);

        return new AccessTokenService($jwtTokenConfig);
    }
}
