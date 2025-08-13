<?php declare(strict_types=1);

namespace ownHackathon\UnitTest\Mock\Constants;

class AccountAccessAuth
{
    public const int ID = 1;

    public const int ID_INVALID = 2;

    public const int USER_ID = 1;

    public const int USER_ID_INVALID = 2;

    public const string LABEL = 'valid Label';

    public const string LABEL_INVALID = 'Invalid Label';

    public const string REFRESH_TOKEN = 'Token Test';

    public const string REFRESH_TOKEN_INVALID = 'invalid Account Name';

    public const string USER_AGENT = 'valid User Agent';

    public const string USER_AGENT_INVALID = 'Invalid User Agent';

    public const string CLIENT_IDENT_HASH = 'valid Client Ident Hash';

    public const string CLIENT_IDENT_HASH_INVALID = 'invalid Client Ident Hash';

    public const string CREATED_AT = '9999-12-31 23:59:59';

    public const array VALID_DATA
        = [
            'id' => self::ID,
            'userId' => self::USER_ID,
            'label' => self::LABEL,
            'refreshToken' => self::REFRESH_TOKEN,
            'userAgent' => self::USER_AGENT,
            'clientIdentHash' => self::CLIENT_IDENT_HASH,
            'createdAt' => self::CREATED_AT,
        ];

    public const array INVALID_DATA
        = [
            'id' => self::ID_INVALID,
            'userId' => self::USER_ID_INVALID,
            'label' => self::LABEL_INVALID,
            'refreshToken' => self::REFRESH_TOKEN_INVALID,
            'userAgent' => self::USER_AGENT_INVALID,
            'clientIdentHash' => self::CLIENT_IDENT_HASH_INVALID,
            'createdAt' => self::CREATED_AT,
        ];
}
