<?php declare(strict_types=1);

namespace ownHackathon\Core\SharedKernel\Factory;

use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \ownHackathon\Core\SharedKernel\Utils\UuidFactory();
    }
}
