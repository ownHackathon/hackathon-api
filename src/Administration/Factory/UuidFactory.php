<?php declare(strict_types=1);

namespace Administration\Factory;

use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidFactory
{
    public function __invoke(ContainerInterface $container): UuidInterface
    {
        return  Uuid::uuid4();
    }
}
