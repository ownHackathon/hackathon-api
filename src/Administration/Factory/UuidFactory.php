<?php declare(strict_types=1);

namespace Administration\Factory;

use Ramsey\Uuid\Uuid;
use Psr\Container\ContainerInterface;

class UuidFactory
{
    public function __invoke(ContainerInterface $container): Uuid
    {
        return new Uuid();
    }
}
