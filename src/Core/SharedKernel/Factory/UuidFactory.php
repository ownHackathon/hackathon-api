<?php declare(strict_types=1);

namespace Core\SharedKernel\Factory;

use Core\SharedKernel\Utils\UuidFactoryInterface;

final class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \Core\SharedKernel\Utils\UuidFactory();
    }
}
