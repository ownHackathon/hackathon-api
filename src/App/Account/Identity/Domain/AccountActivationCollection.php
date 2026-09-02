<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

use function sprintf;

final class AccountActivationCollection extends Collection implements AccountActivationCollectionInterface
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
                    $value::class,
                    AccountActivationInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
