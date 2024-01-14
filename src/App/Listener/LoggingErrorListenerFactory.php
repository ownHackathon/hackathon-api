<?php declare(strict_types=1);

namespace App\Listener;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggingErrorListenerFactory
{
    public function __invoke(ContainerInterface $container): LoggingErrorListener
    {
        $logger = $container->get(LoggerInterface::class);

        return new LoggingErrorListener($logger);
    }
}
