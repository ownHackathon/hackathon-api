<?php declare(strict_types=1);

namespace Core\Http;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Core\Http\Factory\ErrorResponseFactory;
use Core\Http\Factory\SwaggerUIHandlerFactory;
use Core\Http\Handler\PingHandler;
use Core\Http\Handler\SwaggerUIHandler;
use Core\Http\Middleware\ApiErrorHandlerMiddleware;
use Core\Http\Middleware\PaginationMiddleware;
use Core\Http\Middleware\RequestCorrelationMiddleware;
use Core\Http\Middleware\RequestLoggingMiddleware;
use Core\Http\Middleware\RouteNotFoundMiddleware;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                    PingHandler::class,
                ],
                'factories' => [
                    SwaggerUIHandler::class => SwaggerUIHandlerFactory::class,
                    ErrorResponseFactory::class => ConfigAbstractFactory::class,
                    ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                    PaginationMiddleware::class => InvokableFactory::class,
                    RequestCorrelationMiddleware::class => ConfigAbstractFactory::class,
                    RequestLoggingMiddleware::class => ConfigAbstractFactory::class,
                    RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                ErrorResponseFactory::class => [LoggerInterface::class],
                ApiErrorHandlerMiddleware::class => [ErrorResponseFactory::class],
                RequestCorrelationMiddleware::class => [UuidFactoryInterface::class],
                RequestLoggingMiddleware::class => [LoggerInterface::class],
                RouteNotFoundMiddleware::class => [LoggerInterface::class],
            ],
        ];
    }
}