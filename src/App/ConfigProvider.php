<?php declare(strict_types=1);

namespace App;

use Envms\FluentPDO\Query;
use Laminas\Hydrator\ReflectionHydrator;
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
                ReflectionHydrator::class,

                Handler\PingHandler::class,

            ],
            'factories' => [
                Handler\IndexHandler::class => ConfigAbstractFactory::class,

                Middleware\TemplateDefaultsMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserMiddleware::class => ConfigAbstractFactory::class,

                Service\UserService::class => ConfigAbstractFactory::class,

                Table\UserTable::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\IndexHandler::class => [
                TemplateRendererInterface::class,
            ],

            Middleware\TemplateDefaultsMiddleware::class => [
                TemplateRendererInterface::class,
            ],
            Middleware\UserMiddleware::class => [
                Service\UserService::class,
            ],

            Service\UserService::class => [
                Table\UserTable::class,
                ReflectionHydrator::class,
            ],

            Table\UserTable::class => [
                Query::class,
            ],
        ];
    }
}
