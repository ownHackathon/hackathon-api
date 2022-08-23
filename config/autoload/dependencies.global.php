<?php declare(strict_types=1);

return [
    'dependencies' => [
        'aliases' => [
            PDO::class => 'database',
            Envms\FluentPDO\Query::class => 'query',
            Ramsey\Uuid\Uuid::class => 'uuid',
            Symfony\Component\Mailer\Mailer::class => 'mailer',
        ],
        'invokables' => [
        ],
        'factories' => [
            'database' => App\Factory\DatabaseFactory::class,
            'query' => App\Factory\QueryFactory::class,
            'uuid' => App\Factory\UuidFactory::class,
            'mailer' => App\Factory\MailFactory::class,
        ],
    ],
];
