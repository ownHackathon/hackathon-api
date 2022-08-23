<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Container\ContainerInterface;

class CsrfTokenHandlerFactory
{
    public function __invoke(ContainerInterface $container): CsrfTokenHandler
    {
        $token = $container->get('config')['token']['csrf'];

        return new CsrfTokenHandler($token['secret'], (int)$token['duration']);
    }
}
