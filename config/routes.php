<?php declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use ownHackathon\App;
use ownHackathon\Core\Enum\Router\RouteIdent;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get(
        path: '/api/ping[/]',
        middleware: [
            App\Handler\PingHandler::class,
        ],
        name: RouteIdent::PING->value
    );
    $app->get(
        path: '/api/token/refresh[/]',
        middleware: [
            App\Middleware\Token\RefreshTokenValidationMiddleware::class,
            App\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class,
            App\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class,
            App\Middleware\Token\RefreshTokenAccountMiddleware::class,
            App\Middleware\Token\GenerateAccessTokenMiddleware::class,
            App\Handler\Account\AccessTokenHandler::class,
        ],
        name: RouteIdent::ACCESS_TOKEN_REFRESH->value
    );
    $app->post(
        path: '/api/account/authentication[/]',
        middleware: [
            App\Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class,
            App\Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class,
            App\Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class,
            App\Middleware\Token\GenerateRefreshTokenMiddleware::class,
            App\Middleware\Token\GenerateAccessTokenMiddleware::class,
            App\Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class,
            App\Handler\Account\AuthenticationHandler::class,
        ],
        name: RouteIdent::ACCOUNT_AUTHENTICATE->value
    );

    $app->post(
        path: '/api/account',
        middleware: [
            App\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
            App\Middleware\Account\RegisterMiddleware::class,
            App\Handler\Account\AccountRegisterHandler::class,
        ],
        name: RouteIdent::ACCOUNT_CREATE->value
    );

    $app->post(
        path: '/api/account/activation/[{token}[/]]',
        middleware: [
            App\Middleware\Account\Validation\ActivationInputValidatorMiddleware::class,
            App\Middleware\Account\ActivationMiddleware::class,
            App\Handler\Account\AccountActivationHandler::class,
        ],
        name: RouteIdent::ACCOUNT_ACTIVATION->value
    );

    $app->post(
        path: '/api/account/password/forgotten[/]',
        middleware: [
            App\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
            App\Middleware\Account\PasswordForgottenMiddleware::class,
            App\Handler\Account\AccountPasswordForgottenHandler::class,
        ],
        name: RouteIdent::ACCOUNT_PASSWORD_FORGOTTEN->value
    );

    $app->patch(
        path: '/api/account/password/[{token}[/]]',
        middleware: [
            App\Middleware\Account\Validation\PasswordInputValidatorMiddleware::class,
            App\Middleware\Account\PasswordChangeMiddleware::class,
            App\Handler\Account\AccountPasswordHandler::class,
        ],
        name: RouteIdent::ACCOUNT_PASSWORD_SET->value
    );

    $app->get(
        path: '/api/account/logout',
        middleware: [
            App\Middleware\Token\AccessTokenValidationMiddleware::class,
            App\Middleware\Account\LogoutMiddleware::class,
            App\Handler\Account\LogoutHandler::class,
        ],
        name: RouteIdent::ACCOUNT_LOGOUT->value
    );
};
