<?php declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use ownHackathon\App\Http\Enum\RouteIdent;
use ownHackathon\App\Http\Handler\PingHandler;
use ownHackathon\App\Http\Handler\SwaggerUIHandler;
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
