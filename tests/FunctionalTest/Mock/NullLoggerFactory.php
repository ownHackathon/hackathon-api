<?php declare(strict_types=1);

namespace ownHackathon\FunctionalTest\Mock;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class NullLoggerFactory
{
    public function __invoke(ContainerInterface $container): LoggerInterface
    {
        return new NullLogger();
    }
}
