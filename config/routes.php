<?php declare(strict_types=1);

use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Mezzio\Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/api/testmail', \App\Handler\System\TestMailHandler::class, \App\Handler\System\TestMailHandler::class);
    $app->get('/api/ping[/]', \App\Handler\System\PingHandler::class, \App\Handler\System\PingHandler::class);

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
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
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
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Event\EventParticipantSubscribeMiddleware::class,
            \App\Handler\Event\EventParticipantSubscribeHandler::class,
        ],
        \App\Handler\Event\EventParticipantSubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->put(
        '/api/event/participant/unsubscribe/{eventId:\d+}[/]',
        [
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Event\EventParticipantUnsubscribeMiddleware::class,
            \App\Handler\Event\EventParticipantUnsubscribeHandler::class,
        ],
        \App\Handler\Event\EventParticipantUnsubscribeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/topic[/]',
        [
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
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
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\Topic\TopicListAvailableMiddleware::class,
            \App\Handler\Topic\TopicListAvailableHandler::class,
        ],
        \App\Handler\Topic\TopicListAvailableHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/me[/]',
        [
            \App\Handler\System\ApiMeHandler::class,
        ],
        \App\Handler\System\ApiMeHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/register[/]',
        [
            \Core\Authentication\Middleware\UserRegisterValidationMiddleware::class,
            \Core\Authentication\Middleware\UserRegisterMiddleware::class,
            \Core\Authentication\Handler\UserRegisterSubmitHandler::class,
        ],
        \Core\Authentication\Handler\UserRegisterSubmitHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/{userUuid}[/]',
        [
            \Core\Authentication\Middleware\IsLoggedInAuthenticationMiddleware::class,
            \App\Middleware\User\UserMiddleware::class,
            \App\Handler\User\UserHandler::class,
        ],
        \App\Handler\User\UserHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/login[/]',
        [
            \Core\Authentication\Middleware\LoginValidationMiddleware::class,
            \Core\Authentication\Middleware\LoginAuthenticationMiddleware::class,
            \Core\Authentication\Handler\LoginHandler::class,
        ],
        \Core\Authentication\Handler\LoginHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/logout[/]',
        [
            \Core\Authentication\Handler\LogoutHandler::class,
        ],
        \Core\Authentication\Handler\LogoutHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/forgotten',
        [
            \Core\Authentication\Middleware\UserPasswordForgottenValidator::class,
            \Core\Authentication\Middleware\UserPasswordForgottenMiddleware::class,
            \Core\Authentication\Handler\UserPasswordForgottonHandler::class,
        ],
        \Core\Authentication\Handler\UserPasswordForgottonHandler::class
    );
    /** ToDo OpenApi */
    $app->get(
        '/api/user/password/{token}[/]',
        [
            \Core\Authentication\Middleware\UserPasswordVerifyTokenMiddleware::class,
            \Core\Authentication\Handler\UserPasswordVerifyTokenHandler::class,
        ],
        \Core\Authentication\Handler\UserPasswordVerifyTokenHandler::class
    );
    /** ToDo OpenApi */
    $app->post(
        '/api/user/password/{token}[/]',
        [
            \Core\Authentication\Middleware\UserPasswordVerifyTokenMiddleware::class,
            \Core\Authentication\Middleware\UserPasswordChangeValidatorMiddleware::class,
            \Core\Authentication\Middleware\UserPasswordChangeMiddleware::class,
            \Core\Authentication\Handler\UserPasswordChangeHandler::class,
        ],
        \Core\Authentication\Handler\UserPasswordChangeHandler::class
    );
};
