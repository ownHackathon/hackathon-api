<?php declare(strict_types=1);

use ownHackathon\Core\Clock\Duration;

return [
    'project' => [
        'uri' => 'https:\\dev.ownhackathon.de',
        'senderEmail' => 'no-replay@ownhackathon.de',
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
            'subscribe_delay' => Duration::HALF_MINUTE,
        ],
    ],
];
