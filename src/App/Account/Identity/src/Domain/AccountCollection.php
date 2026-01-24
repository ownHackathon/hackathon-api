<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use InvalidArgumentException;
use Shared\Utils\Collection;

use function get_class;
use function sprintf;

class AccountCollection extends Collection implements AccountCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof AccountInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_class($value),
                    AccountInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
