<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Constants;

class Token
{
    public const string ACCESS_TOKEN_VALID = 'valid Access Token';

    public const string REFRESH_TOKEN_VALID = 'valid Refresh Token';

    public static function getTokenStruct(): array
    {
        return [
            'key' => 'token secret',
            'algorithmus' => 'HS512',
            'duration' => 60 * 60 * 24 * 7 * 12,
            'iss' => 'Issuer of the token',
            'aud' => 'recipients of the token',
        ];
    }
}
