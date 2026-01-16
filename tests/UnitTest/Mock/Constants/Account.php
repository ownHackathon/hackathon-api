<?php declare(strict_types=1);

namespace UnitTest\Mock\Constants;

class Account
{
    public const int ID = 1;

    public const int ID_INVALID = 2;

    public const string UUID = '018b9740db6c7126b19c3ab9de2c001c';

    public const string UUID_INVALID = '018b97468d1a73eca5177f94dda637d3';

    public const int ROLE_ID = 1;

    public const int ROLE_ID_INVALID = -1;

    public const string NAME = 'Account Name';

    public const string NAME_INVALID = 'invalid Account Name';

    public const string PASSWORD = '$2y$10$4dye9Kn.UQkHDalnu9FS2uGwPgCZfrB0qYYJxUIcD8SdUQi8xDtp6';

    public const string PASSWORD_STRING = 'valid Password';

    public const string PASSWORD_INVALID = '$2y$10$kVJIMKENYOzFLkEpvKORfOAhi02CXqK14EvK0dBWvDBchDTA4807i';

    public const string PASSWORD_INVALID_STRING = 'invalid Password';

    public const string EMAIL = 'valid@example.com';

    public const string EMAIL_INVALID = 'invalid@example.com';

    public const string REGISTERED = '9999-12-31 23:59:59';

    public const string LAST_ACTION = '9999-12-31 23:59:59';

    public const array VALID_DATA
        = [
            'id' => self::ID,
            'uuid' => self::UUID,
            'name' => self::NAME,
            'password' => self::PASSWORD,
            'email' => self::EMAIL,
            'registeredAt' => self::REGISTERED,
            'lastActionAt' => self::LAST_ACTION,
        ];

    public const array INVALID_DATA
        = [
            'id' => self::ID_INVALID,
            'uuid' => self::UUID_INVALID,
            'name' => self::NAME_INVALID,
            'password' => self::PASSWORD_INVALID,
            'email' => self::EMAIL_INVALID,
            'registeredAt' => self::REGISTERED,
            'lastActionAt' => self::LAST_ACTION,
        ];
}
