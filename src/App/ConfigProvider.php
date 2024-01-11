<?php declare(strict_types=1);

namespace App;

use Administration\Service\EMail\TopicCreateEMailService;
use App\Handler\Authentication\LoginHandlerFactory;
use App\Handler\Authentication\UserPasswordForgottonHandlerFactory;
use App\Hydrator\ClassMethodsHydratorFactory;
use App\Hydrator\DateTimeFormatterStrategyFactory;
use App\Hydrator\NullableStrategyFactory;
use App\Hydrator\ReflectionHydrator;
use App\Middleware\Event\EventCreateMiddlewareFactory;
use App\Service\Authentication\ApiAccessService;
use App\Service\Authentication\ApiAccessServiceFactory;
use App\Service\Authentication\LoginAuthenticationService;
use App\Service\Core\TokenService;
use App\Service\Event\EventService;
use App\Service\Event\EventServiceFactory;
use App\Service\Participant\ParticipantService;
use App\Service\Participant\ParticipantServiceFactory;
use App\Service\Project\ProjectService;
use App\Service\Project\ProjectServiceFactory;
use App\Service\Topic\TopicPoolService;
use App\Service\User\UserService;
use App\Service\User\UserServiceFactory;
use App\Table\TopicPoolTable;
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
use App\Validator\LoginValidator;
use App\Validator\PasswordForgottenEmailValidator;
use App\Validator\RegisterValidator;
use App\Validator\TopicCreateValidator;
use App\Validator\UserPasswordChangeValidator;
use Envms\FluentPDO\Query;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Laminas\Hydrator\Strategy\NullableStrategy;
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
                ReflectionHydrator::class,
            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                DateTimeFormatterStrategy::class => DateTimeFormatterStrategyFactory::class,
                NullableStrategy::class => NullableStrategyFactory::class,

                Handler\Authentication\LoginHandler::class => LoginHandlerFactory::class,
                Handler\Authentication\UserPasswordForgottonHandler::class => UserPasswordForgottonHandlerFactory::class,
                Handler\Event\EventHandler::class => ConfigAbstractFactory::class,
                Handler\Event\EventParticipantSubscribeHandler::class => ConfigAbstractFactory::class,
                Handler\Topic\TopicCreateHandler::class => ConfigAbstractFactory::class,
                Handler\User\UserHandler::class => ConfigAbstractFactory::class,
                Handler\Core\TestMailHandler::class => ConfigAbstractFactory::class,

                Middleware\Authentication\ApiAccessMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\JwtAuthenticationMiddleware::class => Middleware\Authentication\JwtAuthenticationMiddlewareFactory::class,
                Middleware\Authentication\LoginAuthenticationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\LoginValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserPasswordChangeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserPasswordChangeValidatorMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserPasswordForgottenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserPasswordForgottenValidator::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserPasswordVerifyTokenMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserRegisterMiddleware::class => ConfigAbstractFactory::class,
                Middleware\Authentication\UserRegisterValidationMiddleware::class => ConfigAbstractFactory::class,
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

                Service\Authentication\ApiAccessService::class => ApiAccessServiceFactory::class,
                Service\Event\EventService::class => EventServiceFactory::class,
                Service\Participant\ParticipantService::class => ParticipantServiceFactory::class,
                Service\Project\ProjectService::class => ProjectServiceFactory::class,
                Service\Topic\TopicPoolService::class => ConfigAbstractFactory::class,
                Service\User\UserService::class => UserServiceFactory::class,

                Table\EventTable::class => ConfigAbstractFactory::class,
                Table\ParticipantTable::class => ConfigAbstractFactory::class,
                Table\ProjectTable::class => ConfigAbstractFactory::class,
                Table\TopicPoolTable::class => ConfigAbstractFactory::class,
                Table\UserTable::class => ConfigAbstractFactory::class,

                Validator\EventCreateValidator::class => ConfigAbstractFactory::class,
                Validator\LoginValidator::class => ConfigAbstractFactory::class,
                Validator\PasswordForgottenEmailValidator::class => ConfigAbstractFactory::class,
                Validator\RegisterValidator::class => ConfigAbstractFactory::class,
                Validator\TopicCreateValidator::class => ConfigAbstractFactory::class,
                Validator\UserPasswordChangeValidator::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Middleware\Authentication\UserRegisterMiddleware::class => [
                UserService::class,
                ClassMethodsHydrator::class,
            ],
            Middleware\Authentication\ApiAccessMiddleware::class => [
                ApiAccessService::class,
            ],
            Middleware\Authentication\UserPasswordForgottenMiddleware::class => [
                UserService::class,
                TokenService::class,
            ],
            Middleware\Authentication\UserPasswordChangeMiddleware::class => [
                UserService::class,
            ],
            Middleware\Authentication\UserPasswordChangeValidatorMiddleware::class => [
                UserPasswordChangeValidator::class,
            ],
            Middleware\Authentication\UserPasswordForgottenValidator::class => [
                PasswordForgottenEmailValidator::class,
            ],
            Middleware\Authentication\UserPasswordVerifyTokenMiddleware::class => [
                UserService::class,
            ],
            Middleware\Authentication\UserRegisterValidationMiddleware::class => [
                RegisterValidator::class,
            ],
            Middleware\Authentication\LoginAuthenticationMiddleware::class => [
                UserService::class,
                LoginAuthenticationService::class,
            ],
            Middleware\Authentication\LoginValidationMiddleware::class => [
                LoginValidator::class,
            ],
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
            Handler\Core\TestMailHandler::class => [
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
            Service\Topic\TopicPoolService::class => [
                TopicPoolTable::class,
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
            Validator\LoginValidator::class => [
                UsernameInput::class,
                PasswordInput::class,
            ],
            Validator\PasswordForgottenEmailValidator::class => [
                EmailInput::class,
            ],
            Validator\RegisterValidator::class => [
                EmailInput::class,
            ],
            Validator\TopicCreateValidator::class => [
                TopicInput::class,
                TopicDescriptionInput::class,
            ],
            Validator\UserPasswordChangeValidator::class => [
                PasswordInput::class,
            ],
        ];
    }
}
