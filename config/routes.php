<?php declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use ownHackathon\Core\Shared\Domain\Enum\Router\RouteIdent;
use ownHackathon\Core\Shared\Handler\PingHandler;
use ownHackathon\Core\Shared\Handler\SwaggerUIHandler;
use Psr\Container\ContainerInterface;

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
