<?php declare(strict_types=1);

namespace ownHackathon\Core\Listener;

use Laminas\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;

readonly class LoggingErrorListenerDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback): ErrorHandler
    {
        $listener = $container->get(LoggingErrorListener::class);
        $errorHandler = $callback();
        $errorHandler->attachListener($listener);
        return $errorHandler;
    }
}
