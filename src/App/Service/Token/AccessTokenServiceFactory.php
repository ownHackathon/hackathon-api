<?php declare(strict_types=1);

namespace ownHackathon\App\Service\Token;

use Psr\Container\ContainerInterface;
use ownHackathon\App\DTO\JwtTokenConfig;

readonly class AccessTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): AccessTokenService
    {
        $jwtTokenConfig = $container->get('config')['jwt_token']['access'];
        $jwtTokenConfig = JwtTokenConfig::createFromArray($jwtTokenConfig);

        return new AccessTokenService($jwtTokenConfig);
    }
}
