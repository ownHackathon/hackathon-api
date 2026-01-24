<?php declare(strict_types=1);

namespace Shared\Domain\Enum\Router;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case ACCOUNT_CREATE = 'account.create';
    case ACCOUNT_ACTIVATION = 'account.activation';
    case ACCOUNT_AUTHENTICATE = 'account.authenticate';
    case ACCOUNT_PASSWORD_SET = 'account.set.password';
    case ACCOUNT_PASSWORD_FORGOTTEN = 'account.forgotten.password';
    case ACCOUNT_LOGOUT = 'account.logout';
    case ACCOUNT_LIST_ALL = 'account.list.all';
    case ACCESS_TOKEN_REFRESH = 'refresh.access.token';

    case SWAGGER_UI = 'swagger-ui.ui';
}
