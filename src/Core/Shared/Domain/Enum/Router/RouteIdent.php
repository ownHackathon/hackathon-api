<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Domain\Enum\Router;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case SWAGGER_UI = 'swagger-ui.ui';
}
