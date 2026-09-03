<?php declare(strict_types=1);

use Core\Clock\Duration;

return [
    'jwt_token' => [
        'refresh' => [
            'key' => 'ixo>+W%!Rf/\@)m2UMok:/A_gL<dz.v*',
            'algorithmus' => 'HS512',
            'duration' => Duration::TWELVE_WEEKS,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
        'access' => [
            'key' => 'b:?Y@5JCWF:yi{o>irc(3$HFcR-#b\SA',
            'algorithmus' => 'HS512',
            'duration' => Duration::HOUR,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
    ],
];
