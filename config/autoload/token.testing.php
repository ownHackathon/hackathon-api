<?php declare(strict_types=1);

return [
    'jwt_token' => [
        'refresh' => [
            'key' => 'ixo>+W%!Rf/\@)m2UMok:/A_gL<dz.v*',
            'algorithmus' => 'HS512',
            'duration' => 60 * 60 * 60 * 100000,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
        'access' => [
            'key' => 'b:?Y@5JCWF:yi{o>irc(3$HFcR-#b\SA',
            'algorithmus' => 'HS512',
            'duration' => 60 * 60,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
    ],
];
