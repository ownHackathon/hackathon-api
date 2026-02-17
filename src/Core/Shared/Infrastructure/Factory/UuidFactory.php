<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\Factory;

use Exdrals\Core\Shared\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \Exdrals\Core\Shared\Utils\UuidFactory();
    }
}
