<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

use function get_debug_type;
use function sprintf;

final class AccountActivationCollection extends Collection implements AccountActivationCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof AccountActivationInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_debug_type($value),
                    AccountActivationInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
