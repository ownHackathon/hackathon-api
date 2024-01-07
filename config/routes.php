<?php declare(strict_types=1);

use App\Handler\PingHandler;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Mezzio\Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/api/testmail',App\Handler\TestMailHandler::class,App\Handler\TestMailHandler::class);
    $app->get('/api/ping[/]', PingHandler::class, PingHandler::class);

    /** ToDo OpenApi */
    $app->get(
        '/api/event[/]',
        [
            App\Middleware\Event\EventListMiddleware::class,
            App\Handler\EventListHandler::class,
        ],
        App\Handler\EventListHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/event[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\Event\EventCreateValidationMiddleware::class,
            App\Middleware\Event\EventCreateMiddleware::class,
            App\Handler\EventCreateHandler::class,
        ],
        App\Handler\EventCreateHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/event/{eventId:\d+}[/]',
        [
            App\Middleware\Event\EventMiddleware::class,
            App\Handler\EventHandler::class,
        ],
        App\Handler\EventHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/event/{eventName}[/]',
        [
            App\Middleware\Event\EventNameMiddleware::class,
            App\Handler\EventNameHandler::class,
        ],
        App\Handler\EventNameHandler::class
    );
    /** ToDo OpenApi */
    $app->put(
        '/api/event/participant/subscribe/{eventId:\d+}[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\Event\EventParticipantSubscribeMiddleware::class,
            App\Handler\EventParticipantSubscribeHandler::class,
        ],
        App\Handler\EventParticipantSubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->put(
        '/api/event/participant/unsubscribe/{eventId:\d+}[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\Event\EventParticipantUnsubscribeMiddleware::class,
            App\Handler\EventParticipantUnsubscribeHandler::class,
        ],
        App\Handler\EventParticipantUnsubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/topic[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\Topic\TopicCreateValidationMiddleware::class,
            App\Middleware\Topic\TopicCreateSubmitMiddleware::class,
            App\Handler\TopicCreateHandler::class,
        ],
        App\Handler\TopicCreateHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/topics/available[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\Topic\TopicListAvailableMiddleware::class,
            App\Handler\TopicListAvailableHandler::class,
        ],
        App\Handler\TopicListAvailableHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/me[/]',
        [
            Authentication\Handler\ApiMeHandler::class,
        ],
        Authentication\Handler\ApiMeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/register[/]',
        [
            Authentication\Middleware\UserRegisterValidationMiddleware::class,
            Authentication\Middleware\UserRegisterMiddleware::class,
            Authentication\Handler\UserRegisterSubmitHandler::class,
        ],
        Authentication\Handler\UserRegisterSubmitHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/{userUuid}[/]',
        [
            Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            App\Middleware\UserMiddleware::class,
            App\Handler\UserHandler::class,
        ],
        App\Handler\UserHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/login[/]',
        [
            Authentication\Middleware\LoginValidationMiddleware::class,
            Authentication\Middleware\LoginAuthenticationMiddleware::class,
            Authentication\Handler\LoginHandler::class,
        ],
        Authentication\Handler\LoginHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/logout[/]',
        [
            Authentication\Handler\LogoutHandler::class,
        ],
        Authentication\Handler\LogoutHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/forgotten',
        [
            Authentication\Middleware\UserPasswordForgottenValidator::class,
            Authentication\Middleware\UserPasswordForgottenMiddleware::class,
            Authentication\Handler\UserPasswordForgottonHandler::class,
        ],
        Authentication\Handler\UserPasswordForgottonHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/password/{token}[/]',
        [
            Authentication\Middleware\UserPasswordVerifyTokenMiddleware::class,
            Authentication\Handler\UserPasswordVerifyTokenHandler::class,
        ],
        Authentication\Handler\UserPasswordVerifyTokenHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/{token}[/]',
        [
            Authentication\Middleware\UserPasswordVerifyTokenMiddleware::class,
            Authentication\Middleware\UserPasswordChangeValidatorMiddleware::class,
            Authentication\Middleware\UserPasswordChangeMiddleware::class,
            Authentication\Handler\UserPasswordChangeHandler::class,
        ],
        Authentication\Handler\UserPasswordChangeHandler::class
    );
};
