<?php

declare(strict_types=1);

use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Application;
use Mezzio\Cors\Middleware\CorsMiddleware;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\BodyParams\BodyParamsMiddleware;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\MiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Psr\Container\ContainerInterface;
use ownHackathon\App\Middleware\Account\RequestAuthenticationMiddleware;
use ownHackathon\App\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use ownHackathon\Core\Middleware\RouteNotFoundMiddleware;

/**
 * Setup middleware pipeline:
 */

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->pipe('/api', [
        ErrorHandler::class,
        ServerUrlMiddleware::class,
        BodyParamsMiddleware::class,

        CorsMiddleware::class,
        RouteMiddleware::class,

        ImplicitHeadMiddleware::class,
        ImplicitOptionsMiddleware::class,
        MethodNotAllowedMiddleware::class,

        UrlHelperMiddleware::class,

        ClientIdentificationMiddleware::class,
        RequestAuthenticationMiddleware::class,

        DispatchMiddleware::class,

        RouteNotFoundMiddleware::class,
        NotFoundHandler::class,
    ]);
};
