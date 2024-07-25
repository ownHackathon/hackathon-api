<?php declare(strict_types=1);

namespace Core\Handler;

use Psr\Container\ContainerInterface;

readonly class LoginHandlerFactory
{
    public function __invoke(ContainerInterface $container): LoginHandler
    {
        $token = $container->get('config')['token']['auth'];

        return new LoginHandler($token['secret'], (int)$token['duration']);
    }
}
