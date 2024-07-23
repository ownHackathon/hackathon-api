<?php declare(strict_types=1);

namespace App;

use App\Middleware\Event\EventCreateMiddlewareFactory;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Repository\ProjectRepository;
use App\Repository\TopicPoolRepository;
use App\Service\EMail\TopicCreateEMailService;
use App\Service\EMail\TopicCreateEMailServiceFactory;
use App\Service\Event\EventService;
use App\Service\Event\EventServiceFactory;
use App\Service\Participant\ParticipantService;
use App\Service\Participant\ParticipantServiceFactory;
use App\Service\Project\ProjectService;
use App\Service\Project\ProjectServiceFactory;
use App\Service\Topic\TopicPoolService;
use App\Service\Topic\TopicPoolServiceFactory;
use App\Service\User\UserService;
use App\Service\User\UserServiceFactory;
use App\Table\EventTable;
use App\Table\ParticipantTable;
use App\Table\ProjectTable;
use App\Table\TopicPoolTable;
use App\Validator\EventCreateValidator;
use App\Validator\Input\Event\EventDescriptionInput;
use App\Validator\Input\Event\EventDurationInput;
use App\Validator\Input\Event\EventStartTimeInput;
use App\Validator\Input\Event\EventTextInput;
use App\Validator\Input\Event\EventTitleInput;
use App\Validator\Input\Topic\TopicDescriptionInput;
use App\Validator\Input\Topic\TopicInput;
use App\Validator\TopicCreateValidator;
use Core\Authentication\Service\LoginAuthenticationService;
use Core\Hydrator\ReflectionHydrator;
use Envms\FluentPDO\Query;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Ramsey\Uuid\Uuid;
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
                LoginAuthenticationService::class,
                EventDescriptionInput::class,
                EventDurationInput::class,
                EventStartTimeInput::class,
                EventTextInput::class,
                EventTitleInput::class,
                TopicDescriptionInput::class,
                TopicInput::class,
            ],
            'aliases' => [
                EventRepository::class => EventTable::class,
                ParticipantRepository::class => ParticipantTable::class,
                ProjectRepository::class => ProjectTable::class,
                TopicPoolRepository::class => TopicPoolTable::class,
            ],
            'factories' => [
                Handler\Event\EventHandler::class => ConfigAbstractFactory::class,
                Handler\Event\EventParticipantSubscribeHandler::class => ConfigAbstractFactory::class,
                Handler\Topic\TopicCreateHandler::class => ConfigAbstractFactory::class,
                Handler\User\UserHandler::class => ConfigAbstractFactory::class,
                Handler\System\TestMailHandler::class => ConfigAbstractFactory::class,

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
                Middleware\Topic\TopicCreateSubmitMiddleware::class => ConfigAbstractFactory::class,
                Middleware\User\UpdateLastUserActionTimeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\User\UserMiddleware::class => ConfigAbstractFactory::class,

                Service\EMail\TopicCreateEMailService::class => TopicCreateEMailServiceFactory::class,
                Service\Event\EventService::class => EventServiceFactory::class,
                Service\Participant\ParticipantService::class => ParticipantServiceFactory::class,
                Service\Project\ProjectService::class => ProjectServiceFactory::class,
                Service\Topic\TopicPoolService::class => TopicPoolServiceFactory::class,
                Service\User\UserService::class => UserServiceFactory::class,

                Table\EventTable::class => ConfigAbstractFactory::class,
                Table\ParticipantTable::class => ConfigAbstractFactory::class,
                Table\ProjectTable::class => ConfigAbstractFactory::class,
                Table\TopicPoolTable::class => ConfigAbstractFactory::class,

                EventCreateValidator::class => ConfigAbstractFactory::class,
                TopicCreateValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\Event\EventHandler::class => [
                UserService::class,
                ParticipantService::class,
                ProjectService::class,
                TopicPoolService::class,
            ],
            Handler\Event\EventParticipantSubscribeHandler::class => [
                ParticipantService::class,
                ProjectService::class,
            ],
            Handler\Topic\TopicCreateHandler::class => [
                TopicCreateEMailService::class,
            ],
            Handler\User\UserHandler::class => [
                ClassMethodsHydrator::class,
            ],
            Handler\System\TestMailHandler::class => [
                Mailer::class,
            ],
            Middleware\Event\EventCreateValidationMiddleware::class => [
                EventCreateValidator::class,
            ],
            Middleware\Event\EventListMiddleware::class => [
                EventService::class,
                UserService::class,
            ],
            Middleware\Event\EventMiddleware::class => [
                EventService::class,
            ],
            Middleware\Event\EventNameMiddleware::class => [
                EventService::class,
            ],
            Middleware\Event\EventParticipantSubscribeMiddleware::class => [
                ParticipantService::class,
                EventService::class,
            ],
            Middleware\Event\EventParticipantUnsubscribeMiddleware::class => [
                ParticipantService::class,
                EventService::class,
            ],
            Middleware\Project\ProjectMiddleware::class => [
                ProjectService::class,
            ],
            Middleware\Project\ProjectOwnerMiddleware::class => [
                UserService::class,
            ],
            Middleware\Project\ProjectParticipantMiddleware::class => [
                ParticipantService::class,
            ],
            Middleware\Topic\TopicCreateValidationMiddleware::class => [
                TopicCreateValidator::class,
            ],
            Middleware\Topic\TopicEntryStatisticMiddleware::class => [
                TopicPoolService::class,
            ],
            Middleware\Topic\TopicListAvailableMiddleware::class => [
                TopicPoolService::class,
            ],
            Middleware\Topic\TopicListMiddleware::class => [
                TopicPoolService::class,
            ],
            Middleware\Topic\TopicCreateSubmitMiddleware::class => [
                TopicPoolService::class,
                ReflectionHydrator::class,
                Uuid::class,
            ],
            Middleware\User\UpdateLastUserActionTimeMiddleware::class => [
                UserService::class,
            ],
            Middleware\User\UserMiddleware::class => [
                UserService::class,
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

            EventCreateValidator::class => [
                EventTitleInput::class,
                EventDescriptionInput::class,
                EventTextInput::class,
                EventStartTimeInput::class,
                EventDurationInput::class,
            ],
            TopicCreateValidator::class => [
                TopicInput::class,
                TopicDescriptionInput::class,
            ],
        ];
    }
}
