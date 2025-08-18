<?php

declare(strict_types=1);

use Laminas\Stratigility\Middleware\ErrorHandler;
use ownHackathon\Core\Factory\DatabaseFactory;
use ownHackathon\Core\Factory\MailFactory;
use ownHackathon\Core\Factory\QueryFactory;
use ownHackathon\Core\Factory\UuidFactory;
use ownHackathon\Core\Listener\LoggingErrorListenerDelegatorFactory;
use ownHackathon\Core\Logger\LoggerFactory;

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            Psr\Log\LoggerInterface::class => 'logger',
            ownHackathon\Core\Utils\UuidFactoryInterface::class => 'uuid',
            Symfony\Component\Mailer\MailerInterface::class => 'mailer',
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            'database' => DatabaseFactory::class,
            'query' => QueryFactory::class,
            'logger' => LoggerFactory::class,
            'uuid' => UuidFactory::class,
            'mailer' => MailFactory::class,
        ],
    ],
];
