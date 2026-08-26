<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Infrastructure\Factory;

use ownHackathon\Core\Shared\Utils\UuidFactoryInterface;

class UuidFactory
{
    public function __invoke(): UuidFactoryInterface
    {
        return new \ownHackathon\Core\Shared\Utils\UuidFactory();
    }
}
