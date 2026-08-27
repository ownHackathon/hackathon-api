<?php declare(strict_types=1);

namespace ownHackathon\App\Http\Enum;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case SWAGGER_UI = 'swagger-ui.ui';
}
