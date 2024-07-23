<?php declare(strict_types=1);

namespace App\Hydrator\Strategy;

use Laminas\Hydrator\Strategy\Exception\InvalidArgumentException;
use Laminas\Hydrator\Strategy\StrategyInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use function is_string;
use function sprintf;

readonly class UuidStrategy implements StrategyInterface
{
    public function extract($value, ?object $object = null)
    {
        if (!$value instanceof UuidInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Value must be a %s; %s provided',
                    UuidInterface::class,
                    get_debug_type($value)
                )
            );
        }

        return $value->getHex()->toString();
    }

    public function hydrate($value, ?array $data)
    {
        if ($value instanceof UuidInterface) {
            return $value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Value must be string; %s provided',
                    get_debug_type($value)
                )
            );
        }

        return Uuid::fromString($value);
    }
}
