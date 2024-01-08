<?php declare(strict_types=1);

return [
    'project' => [
        'uri' => 'build.hackathon.exdrals.de',
    ],
    'api' => [
        'access' => [
            'domain' => [
                'whitelist' => [
                    'build.hackathon.exdrals.de',
                    'hackathon.exdrals.de',
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
