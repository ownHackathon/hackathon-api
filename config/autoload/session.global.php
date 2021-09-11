<?php declare(strict_types=1);

return [
    'session' => [
        'persistence' => [
            'ext' => [
                'non_locking' => true, // true|false, true => marks read_and_close as true
                'delete_cookie_on_empty_session' => true, // true|false
            ],
        ],
    ],
];
