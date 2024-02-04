<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Service\User\UserService;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

readonly class JwtAuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthenticationMiddleware
    {
        $userService = $container->get(UserService::class);
        $token = $container->get('config')['token']['auth'];
        $logger = $container->get(LoggerInterface::class);

        return new JwtAuthenticationMiddleware(
            $userService,
            $token['secret'],
            $token['algorithmus'],
            $logger,
        );
    }
}
