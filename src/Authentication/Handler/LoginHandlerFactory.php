<?php declare(strict_types=1);

namespace Authentication\Handler;

use Mezzio\Template\TemplateRendererInterface;
use Psr\Container\ContainerInterface;

class LoginHandlerFactory
{
    public function __invoke(ContainerInterface $container): LoginHandler
    {
        $templateRenderer = $container->get(TemplateRendererInterface::class);
        $token = $container->get('config')['token'];

        return new LoginHandler($templateRenderer, $token['secret'], (int)$token['duration']);
    }
}
