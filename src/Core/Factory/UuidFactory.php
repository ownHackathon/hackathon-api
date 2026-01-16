<?php declare(strict_types=1);

namespace Core\Factory;

use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly class UuidFactory
{
    public function __invoke(ContainerInterface $container): UuidInterface
    {
        return Uuid::uuid7();
    }
}
