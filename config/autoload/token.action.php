<?php declare(strict_types=1);

use Core\Clock\Duration;

return [
    'jwt_token' => [
        'refresh' => [
            'key' => '5zc]}0IQKHorS]EsrVg9LIch=rlBpS@T265vd>j*Df>O}=Pio*ShjY2wU/,L,AHnWb69*QRY',
            'algorithmus' => 'HS512',
            'duration' => Duration::TWELVE_WEEKS,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
        'access' => [
            'key' => 'Z9q6vPCe^VX)yIRF8s)R6G&9bF}vl<J_lqS,)u,{MIm+lt#L6C,pg]>9NPbq2ns?]^.pmeoH',
            'algorithmus' => 'HS512',
            'duration' => Duration::FIVE_MINUTES,
            'iss' => 'localhost',
            'aud' => 'localhost',
        ],
    ],
];
