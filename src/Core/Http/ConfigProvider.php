<?php declare(strict_types=1);

namespace ownHackathon\Core\Http;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use ownHackathon\Core\Http\Factory\ErrorResponseFactory;
use ownHackathon\Core\Http\Handler\PingHandler;
use ownHackathon\Core\Http\Handler\SwaggerUIHandler;
use ownHackathon\Core\Http\Middleware\ApiErrorHandlerMiddleware;
use ownHackathon\Core\Http\Middleware\PaginationMiddleware;
use ownHackathon\Core\Http\Middleware\RequestCorrelationMiddleware;
use ownHackathon\Core\Http\Middleware\RequestLoggingMiddleware;
use ownHackathon\Core\Http\Middleware\RouteNotFoundMiddleware;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
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
                    SwaggerUIHandler::class => static function (ContainerInterface $container): SwaggerUIHandler {
                        /** @var array{swagger_ui?: array{index_file?: string}} $config */
                        $config = $container->get('config');

                        return new SwaggerUIHandler(
                            $config['swagger_ui']['index_file'] ?? '',
                        );
                    },
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