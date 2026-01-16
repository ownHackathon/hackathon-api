<?php declare(strict_types=1);

namespace Core\Utils;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\UuidFactoryInterface as RamseyUuidFactoryInterface;

interface UuidFactoryInterface extends RamseyUuidFactoryInterface
{
    public function uuid7(?DateTimeInterface $dateTime = null): UuidInterface;
}
