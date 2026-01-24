<?php declare(strict_types=1);

namespace Exdrals\Shared\Infrastructure\Factory;

use Exdrals\Shared\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \Exdrals\Shared\Utils\UuidFactory();
    }
}
