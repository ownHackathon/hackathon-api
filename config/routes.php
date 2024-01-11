<?php declare(strict_types=1);

use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Mezzio\Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/api/testmail', \App\Handler\Core\TestMailHandler::class, \App\Handler\Core\TestMailHandler::class);
    $app->get('/api/ping[/]', \App\Handler\Core\PingHandler::class, \App\Handler\Core\PingHandler::class);

    /** ToDo OpenApi */
    $app->get(
        '/api/event[/]',
        [
            \App\Middleware\Event\EventListMiddleware::class,
            \App\Handler\Event\EventListHandler::class,
        ],
        \App\Handler\Event\EventListHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/event[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Event\EventCreateValidationMiddleware::class,
            \App\Middleware\Event\EventCreateMiddleware::class,
            \App\Handler\Event\EventCreateHandler::class,
        ],
        \App\Handler\Event\EventCreateHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/event/{eventId:\d+}[/]',
        [
            \App\Middleware\Event\EventMiddleware::class,
            \App\Handler\Event\EventHandler::class,
        ],
        \App\Handler\Event\EventHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/event/{eventName}[/]',
        [
            \App\Middleware\Event\EventNameMiddleware::class,
            \App\Handler\Event\EventNameHandler::class,
        ],
        \App\Handler\Event\EventNameHandler::class
    );
    /** ToDo OpenApi */
    $app->put(
        '/api/event/participant/subscribe/{eventId:\d+}[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Event\EventParticipantSubscribeMiddleware::class,
            \App\Handler\Event\EventParticipantSubscribeHandler::class,
        ],
        \App\Handler\Event\EventParticipantSubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->put(
        '/api/event/participant/unsubscribe/{eventId:\d+}[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Event\EventParticipantUnsubscribeMiddleware::class,
            \App\Handler\Event\EventParticipantUnsubscribeHandler::class,
        ],
        \App\Handler\Event\EventParticipantUnsubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/topic[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Topic\TopicCreateValidationMiddleware::class,
            \App\Middleware\Topic\TopicCreateSubmitMiddleware::class,
            \App\Handler\Topic\TopicCreateHandler::class,
        ],
        \App\Handler\Topic\TopicCreateHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/topics/available[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Topic\TopicListAvailableMiddleware::class,
            \App\Handler\Topic\TopicListAvailableHandler::class,
        ],
        \App\Handler\Topic\TopicListAvailableHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/me[/]',
        [
            \App\Handler\Core\ApiMeHandler::class,
        ],
        \App\Handler\Core\ApiMeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/register[/]',
        [
            \App\Middleware\Authentication\UserRegisterValidationMiddleware::class,
            \App\Middleware\Authentication\UserRegisterMiddleware::class,
            \App\Handler\Authentication\UserRegisterSubmitHandler::class,
        ],
        \App\Handler\Authentication\UserRegisterSubmitHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/{userUuid}[/]',
        [
            \App\Middleware\Authentication\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\User\UserMiddleware::class,
            \App\Handler\User\UserHandler::class,
        ],
        \App\Handler\User\UserHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/login[/]',
        [
            \App\Middleware\Authentication\LoginValidationMiddleware::class,
            \App\Middleware\Authentication\LoginAuthenticationMiddleware::class,
            \App\Handler\Authentication\LoginHandler::class,
        ],
        \App\Handler\Authentication\LoginHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/logout[/]',
        [
            \App\Handler\Authentication\LogoutHandler::class,
        ],
        \App\Handler\Authentication\LogoutHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/forgotten',
        [
            \App\Middleware\Authentication\UserPasswordForgottenValidator::class,
            \App\Middleware\Authentication\UserPasswordForgottenMiddleware::class,
            \App\Handler\Authentication\UserPasswordForgottonHandler::class,
        ],
        \App\Handler\Authentication\UserPasswordForgottonHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/password/{token}[/]',
        [
            \App\Middleware\Authentication\UserPasswordVerifyTokenMiddleware::class,
            \App\Handler\Authentication\UserPasswordVerifyTokenHandler::class,
        ],
        \App\Handler\Authentication\UserPasswordVerifyTokenHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/{token}[/]',
        [
            \App\Middleware\Authentication\UserPasswordVerifyTokenMiddleware::class,
            \App\Middleware\Authentication\UserPasswordChangeValidatorMiddleware::class,
            \App\Middleware\Authentication\UserPasswordChangeMiddleware::class,
            \App\Handler\Authentication\UserPasswordChangeHandler::class,
        ],
        \App\Handler\Authentication\UserPasswordChangeHandler::class
    );
};
