<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Infrastructure\Service\Token;

use Exdrals\Account\Identity\DTO\Token\JwtTokenConfig;
use Psr\Container\ContainerInterface;

readonly class RefreshTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): RefreshTokenService
    {
        $jwtTokenConfig = $container->get('config')['jwt_token']['refresh'];
        $jwtTokenConfig = JwtTokenConfig::createFromArray($jwtTokenConfig);

        return new RefreshTokenService($jwtTokenConfig);
    }
}
