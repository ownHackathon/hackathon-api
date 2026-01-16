<?php declare(strict_types=1);

use ownHackathon\Core\Logger\LoggerFactory;
use ownHackathon\FunctionalTest\Mock\NullMailerFactory;

return [
    'dependencies' => [
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            ownHackathon\Core\Utils\UuidFactoryInterface::class => 'uuid',
            Psr\Log\LoggerInterface::class => 'logger',
            Symfony\Component\Mailer\MailerInterface::class => 'mailer',
        ],
        'invokables' => [
        ],
        'factories' => [
            'database' => ownHackathon\Core\Factory\DatabaseFactory::class,
            'query' => ownHackathon\Core\Factory\QueryFactory::class,
            'logger' => LoggerFactory::class,
            'uuid' => ownHackathon\Core\Factory\UuidFactory::class,
            'mailer' => NullMailerFactory::class,
        ],
    ],
];
