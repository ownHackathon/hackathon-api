<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Utils;

use DateTimeInterface;
use Ramsey\Uuid\UuidFactoryInterface as RamseyUuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;

interface UuidFactoryInterface extends RamseyUuidFactoryInterface
{
    public function uuid7(?DateTimeInterface $dateTime = null): UuidInterface;
}
