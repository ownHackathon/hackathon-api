<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use Exdrals\Shared\Domain\Account\AccountCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountInterface;
use Exdrals\Shared\Utils\Collection;
use InvalidArgumentException;

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
