<?php declare(strict_types=1);

namespace Authentication\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LoginHandlerFactory
{
    public function __invoke(ContainerInterface $container): LoginHandler
    {
        $token = $container->get('config')['token'];

        return new LoginHandler($token['secret'], (int)$token['duration']);
    }
}
