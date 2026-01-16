<?php

declare(strict_types=1);

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
use App\Middleware\Account\LastAktivityUpdaterMiddleware;
use App\Middleware\Account\RequestAuthenticationMiddleware;
use App\Middleware\ClientIdentification\ClientIdentificationMiddleware;
use Core\Middleware\ApiErrorHandlerMiddleware;
use Core\Middleware\RouteNotFoundMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Setup middleware pipeline:
 */

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->pipe([
        ApiErrorHandlerMiddleware::class,
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
        LastAktivityUpdaterMiddleware::class,

        DispatchMiddleware::class,

        RouteNotFoundMiddleware::class,
        NotFoundHandler::class,
    ]);
};
