<?php declare(strict_types=1);

namespace ownHackathon\Core\Http\Enum;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case SWAGGER_UI = 'swagger-ui.ui';
}
