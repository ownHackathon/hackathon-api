<?php declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;
use ownHackathon\App;
use ownHackathon\Core\Message\RouteName;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get(
        path: '/ping[/]',
        middleware: [
            App\Handler\PingHandler::class,
        ],
        name: RouteName::PING
    );
    $app->get(
        path: '/token/refresh[/]',
        middleware: [
            App\Middleware\Token\RefreshTokenValidationMiddleware::class,
            App\Middleware\Token\RefreshTokenDatabaseExistenceMiddleware::class,
            App\Middleware\Token\RefreshTokenMatchClientIdentificationMiddleware::class,
            App\Middleware\Token\RefreshTokenAccountMiddleware::class,
            App\Middleware\Token\GenerateAccessTokenMiddleware::class,
            App\Handler\Account\AccessTokenHandler::class,
        ],
        name: RouteName::ACCESS_TOKEN_REFRESH
    );
    $app->post(
        path: '/account/authentication[/]',
        middleware: [
            App\Middleware\Account\LoginAuthentication\AuthenticationConditionsMiddleware::class,
            App\Middleware\Account\LoginAuthentication\AuthenticationValidationMiddleware::class,
            App\Middleware\Account\LoginAuthentication\AuthenticationMiddleware::class,
            App\Middleware\Token\GenerateRefreshTokenMiddleware::class,
            App\Middleware\Token\GenerateAccessTokenMiddleware::class,
            App\Middleware\Account\LoginAuthentication\PersistAuthenticationMiddleware::class,
            App\Handler\Account\AuthenticationHandler::class,
        ],
        name: RouteName::ACCOUNT_AUTHENTICATE
    );

    $app->post(
        path: '/account',
        middleware: [
            App\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
            App\Middleware\Account\RegisterMiddleware::class,
            App\Handler\Account\AccountRegisterHandler::class,
        ],
        name: RouteName::ACCOUNT_CREATE
    );

    $app->post(
        path: '/account/activation/[{token}[/]]',
        middleware: [
            App\Middleware\Account\Validation\ActivationInputValidatorMiddleware::class,
            App\Middleware\Account\ActivationMiddleware::class,
            App\Handler\Account\AccountActivationHandler::class,
        ],
        name: RouteName::ACCOUNT_ACTIVATION
    );

    $app->post(
        path: '/account/password/forgotten[/]',
        middleware: [
            App\Middleware\Account\Validation\EmailInputValidatorMiddleware::class,
            App\Middleware\Account\PasswordForgottenMiddleware::class,
            App\Handler\Account\AccountPasswordForgottenHandler::class,
        ],
        name: RouteName::ACCOUNT_PASSWORD_FORGOTTEN
    );

    $app->patch(
        path: '/account/password/[{token}[/]]',
        middleware: [
            App\Middleware\Account\Validation\PasswordInputValidatorMiddleware::class,
            App\Middleware\Account\PasswordChangeMiddleware::class,
            App\Handler\Account\AccountPasswordHandler::class,
        ],
        name: RouteName::ACCOUNT_PASSWORD_SET
    );
};
