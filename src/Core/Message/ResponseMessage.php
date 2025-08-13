<?php declare(strict_types=1);

namespace ownHackathon\Core\Message;

interface ResponseMessage
{
    public const string ACCOUNT_ALREADY_AUTHENTICATED = 'There is currently successful authentication';

    public const string ACCOUNT_UNAUTHORIZED = 'unauthorized Account';

    public const string CLIENT_UNEXPECTED = 'Unexpected Client';

    public const string DATA_INVALID = 'Invalid Data';

    public const string EMAIL_INVALID = 'Invalid E-Mail';

    public const string PASSWORD_INVALID = 'Invalid Password';

    public const string TOKEN_INVALID = 'Invalid Token';

    public const string TOKEN_EXPIRED = 'Expired Token';

    public const string TOKEN_NOT_PERSISTENT = 'Token is not persistent';
}
