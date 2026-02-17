<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Token;

use Exdrals\Identity\DTO\Token\JwtTokenConfig;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountAccessAuthRepositoryInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountRepositoryInterface;
use Psr\Container\ContainerInterface;

readonly class RefreshTokenServiceFactory
{
    public function __invoke(ContainerInterface $container): RefreshTokenService
    {
        $accountRepository = $container->get(AccountRepositoryInterface::class);
        $accessAuthRepository = $container->get(AccountAccessAuthRepositoryInterface::class);
        $accessTokenService = $container->get(AccessTokenService::class);
        $jwtTokenConfig = $container->get('config')['jwt_token']['refresh'];
        $jwtTokenConfig = JwtTokenConfig::createFromArray($jwtTokenConfig);

        return new RefreshTokenService(
            $accountRepository,
            $accessAuthRepository,
            $accessTokenService,
            $jwtTokenConfig
        );
    }
}
