<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Token;

use ownHackathon\App\Account\Identity\Domain\Repository\AccountAccessAuthRepositoryInterface;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountRepositoryInterface;
use ownHackathon\App\Account\Identity\DTO\Token\JwtTokenConfig;
use Psr\Container\ContainerInterface;

readonly final class RefreshTokenServiceFactory
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
            $jwtTokenConfig,
        );
    }
}
