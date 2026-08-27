<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared;

use Envms\FluentPDO\Query;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\Core\Shared\Infrastructure\Service\PaginationTotalPages;
use ownHackathon\Core\Shared\Middleware\PaginationMiddleware;
use ownHackathon\Core\Shared\Validator\Input\VisibilityInput;
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
                VisibilityInput::class,
            ],
            'aliases' => [
            ],
            'factories' => [
                \ownHackathon\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class => ConfigAbstractFactory::class,
                \ownHackathon\Core\Shared\Middleware\ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                \ownHackathon\Core\Shared\Middleware\RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
                \ownHackathon\Core\Shared\Infrastructure\Service\SlugService::class => InvokableFactory::class,
                \ownHackathon\Core\Shared\Middleware\FluentTransactionMiddleware::class => ConfigAbstractFactory::class,
                PaginationMiddleware::class => InvokableFactory::class,
                PaginationTotalPages::class => InvokableFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            \ownHackathon\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class => [
                LoggerInterface::class,
            ],
            \ownHackathon\Core\Shared\Middleware\ApiErrorHandlerMiddleware::class => [
                \ownHackathon\Core\Shared\Infrastructure\Factory\ErrorResponseFactory::class,
            ],
            \ownHackathon\Core\Shared\Middleware\RouteNotFoundMiddleware::class => [
                LoggerInterface::class,
            ],
            \ownHackathon\Core\Shared\Middleware\FluentTransactionMiddleware::class => [
                Query::class,
            ],
        ];
    }
}
