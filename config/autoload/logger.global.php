<?php declare(strict_types=1);

return [
    'logger' => [
        'path' => __DIR__ . '/../../data/log/',
        'retention_days' => 30,
        'email_hash_salt' => 'change-me-in-production',
        'channels' => [
            'account-activity' => [
                'file' => 'account-activity.log',
                'format' => 'line',
            ],
        ],
    ]
];
