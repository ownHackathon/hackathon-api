<?php declare(strict_types=1);

namespace Test;

use App\Handler\PingHandler;
use Authentication\Handler\ApiMeHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/api/ping[/]', PingHandler::class, PingHandler::class);

    $app->get(
        '/api/user/me[/]',
        [
            ApiMeHandler::class,
        ],
        ApiMeHandler::class
    );
};