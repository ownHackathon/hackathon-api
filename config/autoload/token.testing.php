<?php declare(strict_types=1);

return [
    'token' => [
        'auth' => [
            'secret' => '7TtyzSrTbDFZXQ5Mjpj5xvv9iphO5oYy',
            'algorithmus' => 'HS512',
            'duration' => 60 * 60,
            'refresh' => 24 * 60 * 60,
        ],
        'csrf' => [
            'secret' => 'Uq3FFwZEtK41LMF3pkJLoXlyJIzHjalT',
            'algorithmus' => 'HS512',
            'duration' => 60 * 60,
            'refresh' => 24 * 60 * 60,
        ],
    ],
];
