<?php declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;
use Shared\Domain\Enum\Router\RouteIdent;
use Shared\Handler\PingHandler;
use Shared\Handler\SwaggerUIHandler;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get(
        path: '/api/ping[/]',
        middleware: [
            PingHandler::class,
        ],
        name: RouteIdent::PING->value
    );

    $app->get(
        path: '/api[/]',
        middleware: [
            SwaggerUIHandler::class,
        ],
        name: RouteIdent::SWAGGER_UI->value
    );
};
