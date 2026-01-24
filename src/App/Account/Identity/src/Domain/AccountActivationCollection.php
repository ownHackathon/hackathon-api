<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use InvalidArgumentException;
use Shared\Utils\Collection;

use function get_class;
use function sprintf;

class AccountActivationCollection extends Collection implements AccountActivationCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof AccountActivationInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_class($value),
                    AccountActivationInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
