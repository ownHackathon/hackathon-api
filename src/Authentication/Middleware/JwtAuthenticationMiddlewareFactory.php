<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Service\UserService;
use Psr\Container\ContainerInterface;

class JwtAuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthenticationMiddleware
    {
        $userService = $container->get(UserService::class);
        $token = $container->get('config')['token']['auth'];

        return new JwtAuthenticationMiddleware($userService, $token['secret'], $token['algorithmus']);
    }
}
