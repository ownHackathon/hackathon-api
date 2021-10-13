<?php
declare(strict_types=1);

namespace App;

use App\Hydrator\ClassMethodsHydratorFactory;
use Envms\FluentPDO\Query;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Mezzio\Template\TemplateRendererInterface;

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
                Handler\PingHandler::class,
            ],
            'factories' => [
                ClassMethodsHydrator::class => ClassMethodsHydratorFactory::class,
                Handler\EventHandler::class => ConfigAbstractFactory::class,
                Handler\EventAboutHandler::class => ConfigAbstractFactory::class,
                Handler\EventListHandler::class => ConfigAbstractFactory::class,
                Handler\IndexHandler::class => ConfigAbstractFactory::class,
                Handler\ProjectHandler::class => ConfigAbstractFactory::class,
                Handler\UserHandler::class => ConfigAbstractFactory::class,

                Middleware\EventParticipantMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventMiddleware::class => ConfigAbstractFactory::class,
                Middleware\EventListMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ProjectMiddleware::class => ConfigAbstractFactory::class,
                Middleware\ParticipantProjectMiddleware::class => ConfigAbstractFactory::class,
                Middleware\TemplateDefaultsMiddleware::class => ConfigAbstractFactory::class,
                Middleware\UserMiddleware::class => ConfigAbstractFactory::class,

                Service\EventService::class => ConfigAbstractFactory::class,
                Service\ParticipantService::class => ConfigAbstractFactory::class,
                Service\ProjectService::class => ConfigAbstractFactory::class,
                Service\UserService::class => ConfigAbstractFactory::class,

                Table\EventTable::class => ConfigAbstractFactory::class,
                Table\ProjectTable::class => ConfigAbstractFactory::class,
                Table\ParticipantTable::class => ConfigAbstractFactory::class,
                Table\UserTable::class => ConfigAbstractFactory::class,
            ],
        ];
    }

    public function getAbstractFactoryConfig(): array
    {
        return [
            Handler\EventHandler::class => [
                ClassMethodsHydrator::class,
                TemplateRendererInterface::class,
            ],
            Handler\EventAboutHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\EventListHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\IndexHandler::class => [
                TemplateRendererInterface::class,
            ],
            Handler\ProjectHandler::class => [
                ClassMethodsHydrator::class,
                TemplateRendererInterface::class,
            ],
            Handler\UserHandler::class => [
                ClassMethodsHydrator::class,
                TemplateRendererInterface::class,
            ],
            Middleware\EventParticipantMiddleware::class => [
                Service\ParticipantService::class,
                Service\UserService::class,
            ],
            Middleware\EventMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\EventListMiddleware::class => [
                Service\EventService::class,
            ],
            Middleware\ProjectMiddleware::class => [
                Service\ProjectService::class,
            ],
            Middleware\ParticipantProjectMiddleware::class => [
                Service\ProjectService::class,
            ],
            Middleware\TemplateDefaultsMiddleware::class => [
                TemplateRendererInterface::class,
            ],
            Middleware\UserMiddleware::class => [
                Service\UserService::class,
            ],

            Service\EventService::class => [
                Table\EventTable::class,
                ClassMethodsHydrator::class,
            ],
            Service\ProjectService::class => [
                Table\ProjectTable::class,
                ClassMethodsHydrator::class,
            ],
            Service\ParticipantService::class => [
                Table\ParticipantTable::class,
                ClassMethodsHydrator::class,
            ],
            Service\UserService::class => [
                Table\UserTable::class,
                ClassMethodsHydrator::class,
            ],

            Table\EventTable::class => [
                Query::class,
            ],
            Table\ProjectTable::class => [
                Query::class,
            ],
            Table\ParticipantTable::class => [
                Query::class,
            ],
            Table\UserTable::class => [
                Query::class,
            ],
        ];
    }
}
