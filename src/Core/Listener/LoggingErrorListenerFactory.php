<?php declare(strict_types=1);

namespace Core\Listener;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

readonly class LoggingErrorListenerFactory
{
    public function __invoke(ContainerInterface $container): LoggingErrorListener
    {
        $logger = $container->get(LoggerInterface::class);

        return new LoggingErrorListener($logger);
    }
}
