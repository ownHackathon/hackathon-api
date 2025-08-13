<?php declare(strict_types=1);

namespace ownHackathon\Core\Message;

interface RouteName
{
    public const string PING = 'handler.ping';

    public const string ACCOUNT_CREATE = 'account.create';

    public const string ACCOUNT_ACTIVATION = 'account.activation';

    public const string ACCOUNT_AUTHENTICATE = 'account.authenticate';

    public const string ACCOUNT_PASSWORD_SET = 'account.set.password';

    public const string ACCOUNT_PASSWORD_FORGOTTEN = 'account.forgotten.password';

    public const string ACCOUNT_LIST_ALL = 'account.list.all';

    public const string ACCESS_TOKEN_REFRESH = 'refresh.access.token';
}
