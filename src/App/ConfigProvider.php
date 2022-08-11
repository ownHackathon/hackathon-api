<?php declare(strict_types=1);

namespace App;

use App\Hydrator\ClassMethodsHydratorFactory;
use App\Hydrator\DateTimeFormatterStrategyFactory;
use App\Hydrator\NullableStrategyFactory;
use App\Hydrator\ReflectionHydrator;
use App\Middleware\EventCreateMiddlewareFactory;
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
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Template\TemplateRendererInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'view_helpers' => $this->getViewHelperConfig(),
            'dependencies' => $this->getDependencies(),
            ConfigAbstractFactory::class => $this->getAbstractFactoryConfig(),
        ];
    }

    public function getViewHelperConfig(): array
    {
        return [
            'invokables' => [

            ],
            'aliases' => [
                'canEventSubscribe' => View\Helper\CanEventSubscribe::class,
                'isParticipant' => View\Helper\IsParticipant::class,

            ],
            'factories' => [
                View\Helper\CanEventSubscribe::class => InvokableFactory::class,
                View\Helper\IsParticipant::class => InvokableFactory::class,
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\EventNameHandler::class,
                Handler\EventCreateHandler::class,
                Handler\EventParticipantUnsubsribeHandler::class,
                Handler\PingHandler::class,
                Handler\IndexHandler::class,

                Rating\ProjectRatingCalculator::class,

                Service\TopicVoterService::class,

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
            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                DateTimeFormatterStrategy::class => DateTimeFormatterStrategyFactory::class,
                NullableStrategy::class => NullableStrategyFactory::class,

                Handler\EventHandler::class => ConfigAbstractFactory::class,
                Handler\EventAboutHandler::class => ConfigAbstractFactory::class,
                Handler\EventListHandler::class => ConfigAbstractFactory::class,
                Handler\EventParticipantSubscribeHandler::class => ConfigAbstractFactory::class,
                Handler\ProjectHandler::class => ConfigAbstractFactory::class,
                Handler\TopicHandler::class => ConfigAbstractFactory::class,
                Handler\TopicSubmitHandler::class => ConfigAbstractFactory::class,
                Handler\UserHandler::class => ConfigAbstractFactory::class,

                Middleware\EventCreateMiddleware::class => EventCreateMiddlewareFactory::class,
                Middleware\EventCreateValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventParticipantSubscribeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventParticipantUnsubscribeMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventNameMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventListMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventRatingReleasedMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventTopicVoteMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ParticipantProjectMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectCategoryRatingMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectEventRatingReleasedMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectOwnerMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectParticipantMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TopicCreateValidationMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TopicEntryStatisticMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TopicListAvailableMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TopicListMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TopicSubmitMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserMiddleware::class => ConfigAbstractFactory::class,

                Service\EventRatingCategoryService::class => ConfigAbstractFactory::class,
                Service\EventRatingService::class => ConfigAbstractFactory::class,
                Service\EventService::class => EventServiceFactory::class,
                Service\ParticipantService::class => ParticipantServiceFactory::class,
                Service\ProjectService::class => ProjectServiceFactory::class,
                Service\RatingCategoryService::class => ConfigAbstractFactory::class,
                Service\RatingService::class => ConfigAbstractFactory::class,
                Service\RoleService::class => ConfigAbstractFactory::class,
                Service\TopicPoolService::class => ConfigAbstractFactory::class,
                Service\UserService::class => UserServiceFactory::class,

                Table\EventRatingCategoryTable::class => ConfigAbstractFactory::class,
                Table\EventRatingTable::class => ConfigAbstractFactory::class,
                Table\EventTable::class => ConfigAbstractFactory::class,
                Table\ParticipantTable::class => ConfigAbstractFactory::class,
                Table\ProjectTable::class => ConfigAbstractFactory::class,
                Table\RatingCategoryTable::class => ConfigAbstractFactory::class,
                Table\RatingTable::class => ConfigAbstractFactory::class,
                Table\RoleTable::class => ConfigAbstractFactory::class,
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
            Handler\EventAboutHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\EventListHandler::class => [
                UserService::class,
            ],
            Handler\EventParticipantSubscribeHandler::class => [
                Service\ParticipantService::class,
                Service\ProjectService::class,
            ],
            Handler\ProjectHandler::class => [
                ClassMethodsHydrator::class,
                TemplateRendererInterface::class,
            ],
            Handler\TopicHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\TopicSubmitHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\UserHandler::class => [
                ClassMethodsHydrator::class,
            ],
            Middleware\EventCreateValidationMiddleware::class => [
                EventCreateValidator::class,
            ],
            Middleware\EventListMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\EventMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\EventNameMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\EventParticipantSubscribeMiddleware::class => [
                Service\ParticipantService::class,
                Service\EventService::class,
            ],
            Middleware\EventParticipantUnsubscribeMiddleware::class => [
                Service\ParticipantService::class,
                Service\EventService::class,
            ],
            Middleware\EventRatingReleasedMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\EventTopicVoteMiddleware::class => [
                Service\TopicPoolService::class,
                Service\TopicVoterService::class,
            ],
            Middleware\ParticipantProjectMiddleware::class => [
                Service\ProjectService::class,
                Service\UserService::class,
            ],
            Middleware\ProjectCategoryRatingMiddleware::class => [
                Service\RatingService::class,
                Rating\ProjectRatingCalculator::class,
            ],
            Middleware\ProjectEventRatingReleasedMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\ProjectMiddleware::class => [
                Service\ProjectService::class,
            ],
            Middleware\ProjectOwnerMiddleware::class => [
                Service\UserService::class,
            ],
            Middleware\ProjectParticipantMiddleware::class => [
                Service\ParticipantService::class,
            ],
            Middleware\TopicCreateValidationMiddleware::class => [
                Validator\TopicCreateValidator::class,
            ],
            Middleware\TopicEntryStatisticMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\TopicListAvailableMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\TopicListMiddleware::class => [
                Service\TopicPoolService::class,
            ],
            Middleware\TopicSubmitMiddleware::class => [
                Service\TopicPoolService::class,
                ClassMethodsHydrator::class,
            ],
            Middleware\UserMiddleware::class => [
                Service\UserService::class,
            ],
            Service\EventRatingCategoryService::class => [
                Table\EventRatingCategoryTable::class,
                ReflectionHydrator::class,
            ],
            Service\EventRatingService::class => [
                Table\EventRatingTable::class,
                ReflectionHydrator::class,
            ],
            Service\RatingCategoryService::class => [
                Table\RatingCategoryTable::class,
                ReflectionHydrator::class,
            ],
            Service\RatingService::class => [
                Table\RatingTable::class,
                ReflectionHydrator::class,
            ],
            Service\RoleService::class => [
                Table\RoleTable::class,
                ReflectionHydrator::class,
            ],
            Service\TopicPoolService::class => [
                Table\TopicPoolTable::class,
                ReflectionHydrator::class,
            ],
            Table\EventRatingCategoryTable::class => [
                Query::class,
            ],
            Table\EventRatingTable::class => [
                Query::class,
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
            Table\RatingCategoryTable::class => [
                Query::class,
            ],
            Table\RatingTable::class => [
                Query::class,
            ],
            Table\RoleTable::class => [
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
