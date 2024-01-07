<?php declare(strict_types=1);

namespace Test;

use App\Handler\PingHandler;
use Mezzio\Application;

return static function (Application $app): void {
    $app->get('/api/ping[/]', PingHandler::class, PingHandler::class);
};
