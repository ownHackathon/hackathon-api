<?php declare(strict_types=1);

use App\Listener\LoggingErrorListenerDelegatorFactory;
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
            'database' => App\Factory\DatabaseFactory::class,
            'query' => App\Factory\QueryFactory::class,
            'uuid' => App\Factory\UuidFactory::class,
            'mailer' => App\Factory\MailFactory::class,
            'logger' => App\Factory\LoggerFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
