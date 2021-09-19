<?php
declare(strict_types=1);

namespace Authentication;

use App\Service\UserService;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Template\TemplateRendererInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Service\LoginAuthenticationService::class,
            ],
            'factories' => [
                Handler\LoginHandler::class => ConfigAbstractFactory::class,

                Middleware\LoginAuthenticationMiddleware::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\LoginHandler::class => [
                TemplateRendererInterface::class,
            ],

            Middleware\LoginAuthenticationMiddleware::class => [
                UserService::class,
                Service\LoginAuthenticationService::class,
            ],
        ];
    }
}
