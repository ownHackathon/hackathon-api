<?php declare(strict_types=1);

return [
    'project' => [
        'uri' => 'http://localhost',
    ],
    'api' => [
        'access' => [
            'domain' => [
                'whitelist' => [
                    'localhost',
                ],
            ],
        ],
    ],
    'event' => [
        'participant' => [
            'subscribe_delay' => 30,
        ],
    ],
];
