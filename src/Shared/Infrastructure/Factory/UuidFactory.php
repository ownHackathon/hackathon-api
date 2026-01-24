<?php declare(strict_types=1);

namespace Shared\Infrastructure\Factory;

use Shared\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \Shared\Utils\UuidFactory();
    }
}
