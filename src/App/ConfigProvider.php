<?php declare(strict_types=1);

namespace App;

use App\Hydrator\ClassMethodsHydratorFactory;
use App\Hydrator\DateTimeFormatterStrategyFactory;
use App\Hydrator\NullableStrategyFactory;
use App\Hydrator\ReflectionHydrator;
use App\Middleware\Event\EventCreateMiddlewareFactory;
use App\Service\EventServiceFactory;
use App\Service\ParticipantService;
use App\Service\ParticipantServiceFactory;
use App\Service\ProjectService;
use App\Service\ProjectServiceFactory;
use App\Service\TopicPoolService;
use App\Service\UserService;
use App\Service\UserServiceFactory;
use App\Validator\EventCreateValidator;
use App\Validator\Input\EmailInput;
use App\Validator\Input\EventDescriptionInput;
use App\Validator\Input\EventDurationInput;
use App\Validator\Input\EventStartTimeInput;
use App\Validator\Input\EventTextInput;
use App\Validator\Input\EventTitleInput;
use App\Validator\Input\PasswordInput;
use App\Validator\Input\TopicDescriptionInput;
use App\Validator\Input\TopicInput;
use App\Validator\Input\UsernameInput;
use Envms\FluentPDO\Query;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Symfony\Component\Mailer\Mailer;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\EventNameHandler::class,
                Handler\EventCreateHandler::class,
                Handler\EventParticipantUnsubscribeHandler::class,
                Handler\IndexHandler::class,
                Handler\SwaggerUIHandler::class,

                EmailInput::class,
                EventDescriptionInput::class,
                EventDurationInput::class,
                EventTitleInput::class,
                EventStartTimeInput::class,
                EventTextInput::class,
                PasswordInput::class,
                TopicDescriptionInput::class,
                TopicInput::class,
                UsernameInput::class,

                ReflectionHydrator::class,

                Service\TokenService::class,
            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                DateTimeFormatterStrategy::class => DateTimeFormatterStrategyFactory::class,
                NullableStrategy::class => NullableStrategyFactory::class,

                Handler\EventHandler::class => ConfigAbstractFactory::class,
                Handler\EventListHandler::class => ConfigAbstractFactory::class,
                Handler\EventParticipantSubscribeHandler::class => ConfigAbstractFactory::class,
                Handler\UserHandler::class => ConfigAbstractFactory::class,
                Handler\TestMailHandler::class => ConfigAbstractFactory::class,

                Middleware\Event\EventCreateMiddleware::class => EventCreateMiddlewareFactory::class,
                Middleware\Event\EventCreateValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Event\EventParticipantSubscribeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Event\EventParticipantUnsubscribeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Event\EventMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Event\EventNameMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Event\EventListMiddleware::class => ConfigAbstractFactory::class,

                Middleware\Project\ProjectMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Project\ProjectOwnerMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Project\ProjectParticipantMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Topic\TopicCreateValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Topic\TopicEntryStatisticMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Topic\TopicListAvailableMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Topic\TopicListMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Topic\TopicSubmitMiddleware::class => ConfigAbstractFactory::class,

                Middleware\UpdateLastUserActionTimeMiddleware::class => ConfigAbstractFactory::class,

                Middleware\UserMiddleware::class => ConfigAbstractFactory::class,

                Service\EventService::class => EventServiceFactory::class,
                Service\ParticipantService::class => ParticipantServiceFactory::class,
                Service\ProjectService::class => ProjectServiceFactory::class,
                Service\TopicPoolService::class => ConfigAbstractFactory::class,
                Service\UserService::class => UserServiceFactory::class,

                Table\EventTable::class => ConfigAbstractFactory::class,
                Table\ParticipantTable::class => ConfigAbstractFactory::class,
                Table\ProjectTable::class => ConfigAbstractFactory::class,
                Table\TopicPoolTable::class => ConfigAbstractFactory::class,
                Table\UserTable::class => ConfigAbstractFactory::class,

                Validator\EventCreateValidator::class => ConfigAbstractFactory::class,
                Validator\TopicCreateValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\EventHandler::class => [
                UserService::class,
                ParticipantService::class,
                ProjectService::class,
                TopicPoolService::class,
            ],
            Handler\EventListHandler::class => [
                UserService::class,
            ],
            Handler\EventParticipantSubscribeHandler::class => [
                Service\ParticipantService::class,
                Service\ProjectService::class,
            ],
            Handler\UserHandler::class => [
                ClassMethodsHydrator::class,
            ],
            Handler\TestMailHandler::class => [
                Mailer::class,
            ],
            Middleware\Event\EventCreateValidationMiddleware::class => [
                EventCreateValidator::class,
            ],
            Middleware\Event\EventListMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\Event\EventMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\Event\EventNameMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\Event\EventParticipantSubscribeMiddleware::class => [
                Service\ParticipantService::class,
                Service\EventService::class,
            ],
            Middleware\Event\EventParticipantUnsubscribeMiddleware::class => [
                Service\ParticipantService::class,
                Service\EventService::class,
            ],
            Middleware\Project\ProjectMiddleware::class => [
                Service\ProjectService::class,
            ],
            Middleware\Project\ProjectOwnerMiddleware::class => [
                Service\UserService::class,
            ],
            Middleware\Project\ProjectParticipantMiddleware::class => [
                Service\ParticipantService::class,
            ],
            Middleware\Topic\TopicCreateValidationMiddleware::class => [
                Validator\TopicCreateValidator::class,
            ],
            Middleware\Topic\TopicEntryStatisticMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\Topic\TopicListAvailableMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\Topic\TopicListMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\Topic\TopicSubmitMiddleware::class => [
                Service\TopicPoolService::class,
                ClassMethodsHydrator::class,
            ],
            Middleware\UpdateLastUserActionTimeMiddleware::class => [
                UserService::class,
            ],
            Middleware\UserMiddleware::class => [
                Service\UserService::class,
            ],
            Service\TopicPoolService::class => [
                Table\TopicPoolTable::class,
                ReflectionHydrator::class,
            ],
            Table\EventTable::class => [
                Query::class,
            ],
            Table\ParticipantTable::class => [
                Query::class,
            ],
            Table\ProjectTable::class => [
                Query::class,
            ],
            Table\TopicPoolTable::class => [
                Query::class,
            ],
            Table\UserTable::class => [
                Query::class,
            ],

            Validator\EventCreateValidator::class => [
                EventTitleInput::class,
                EventDescriptionInput::class,
                EventTextInput::class,
                EventStartTimeInput::class,
                EventDurationInput::class,
            ],
            Validator\TopicCreateValidator::class => [
                TopicInput::class,
                TopicDescriptionInput::class,
            ],
        ];
    }
}
