<?php declare(strict_types=1);

use ownHackathon\Core\Clock\Duration;

return [
    'project' => [
        'uri' => 'http://localhost:5173',
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
            'subscribe_delay' => Duration::HALF_MINUTE,
        ],
    ],
];
