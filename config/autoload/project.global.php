<?php declare(strict_types=1);

return [
    'project' => [
        'uri' => 'dev.ownhackathon.de',
    ],
    'api' => [
        'access' => [
            'domain' => [
                'whitelist' => [
                    'build.ownhackathon.de',
                    'dev.ownhackathon.de',
                    'ownhackathon.de',
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
