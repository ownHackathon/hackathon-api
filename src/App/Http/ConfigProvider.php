<?php declare(strict_types=1);

namespace ownHackathon\App\Http;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use ownHackathon\App\Http\Factory\ErrorResponseFactory;
use ownHackathon\App\Http\Handler\PingHandler;
use ownHackathon\App\Http\Handler\SwaggerUIHandler;
use ownHackathon\App\Http\Middleware\ApiErrorHandlerMiddleware;
use ownHackathon\App\Http\Middleware\RouteNotFoundMiddleware;
use Psr\Log\LoggerInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                    PingHandler::class,
                    SwaggerUIHandler::class,
                ],
                'factories' => [
                    ErrorResponseFactory::class => ConfigAbstractFactory::class,
                    ApiErrorHandlerMiddleware::class => ConfigAbstractFactory::class,
                    RouteNotFoundMiddleware::class => ConfigAbstractFactory::class,
                ],
            ],
            ConfigAbstractFactory::class => [
                ErrorResponseFactory::class => [LoggerInterface::class],
                ApiErrorHandlerMiddleware::class => [ErrorResponseFactory::class],
                RouteNotFoundMiddleware::class => [LoggerInterface::class],
            ],
        ];
    }
}
