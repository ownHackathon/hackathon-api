<?php declare(strict_types=1);

namespace Exdrals\Shared;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Log\LoggerInterface;
use Exdrals\Shared;

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
            ],
            'aliases' => [
            ],
            'factories' => [
                Infrastructure\Factory\ErrorResponseFactory::class => ConfigAbstractFactory::class,
                Middleware\ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
                Shared\Infrastructure\Service\SlugService::class => InvokableFactory::class,
                Shared\Middleware\RequireLoginMiddleware::class => InvokableFactory::class,
                Shared\Middleware\FluentTransactionMiddleware::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Infrastructure\Factory\ErrorResponseFactory::class => [
                LoggerInterface::class,
            ],
            Middleware\ApiErrorHandlerMiddleware::class => [
                Infrastructure\Factory\ErrorResponseFactory::class,
            ],
            Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
            Shared\Middleware\FluentTransactionMiddleware::class => [
                Query::class,
            ],
        ];
    }
}
