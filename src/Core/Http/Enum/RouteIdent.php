<?php declare(strict_types=1);

namespace Core\Http\Enum;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case SWAGGER_UI = 'swagger-ui.ui';
}
