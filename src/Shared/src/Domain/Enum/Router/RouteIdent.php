<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Enum\Router;

enum RouteIdent: string
{
    case PING = 'handler.ping';
    case SWAGGER_UI = 'swagger-ui.ui';
}
