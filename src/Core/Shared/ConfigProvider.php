<?php declare(strict_types=1);

namespace Exdrals\Core\Shared;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Psr\Log\LoggerInterface;

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
                \Exdrals\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class => ConfigAbstractFactory::class,
                \Exdrals\Core\Shared\Middleware\ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Core\Shared\Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
                \Exdrals\Core\Shared\Infrastructure\Service\SlugService::class => InvokableFactory::class,
                \Exdrals\Core\Shared\Middleware\FluentTransactionMiddleware::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            \Exdrals\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class => [
                LoggerInterface::class,
            ],
            \Exdrals\Core\Shared\Middleware\ApiErrorHandlerMiddleware::class => [
                \Exdrals\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class,
            ],
            \Exdrals\Core\Shared\Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
            \Exdrals\Core\Shared\Middleware\FluentTransactionMiddleware::class => [
                Query::class,
            ],
        ];
    }
}
