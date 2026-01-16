<?php declare(strict_types=1);

use Core\Listener\LoggingErrorListenerDelegatorFactory;
use Laminas\Stratigility\Middleware\ErrorHandler;

return [
    'dependencies' => [
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            Ramsey\Uuid\Uuid::class => 'uuid',
            Symfony\Component\Mailer\Mailer::class => 'mailer',
            Psr\Log\LoggerInterface::class => 'logger',
        ],
        'invokables' => [
        ],
        'factories' => [
            'database' => \Core\Factory\DatabaseFactory::class,
            'query' => \Core\Factory\QueryFactory::class,
            'uuid' => \Core\Factory\UuidFactory::class,
            'mailer' => \Core\Factory\MailFactory::class,
            'logger' => \Core\Factory\LoggerFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
