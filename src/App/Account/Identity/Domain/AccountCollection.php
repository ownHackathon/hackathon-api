<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

use function get_debug_type;
use function sprintf;

final class AccountCollection extends Collection implements AccountCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof AccountInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_debug_type($value),
                    AccountInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
