<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use Exdrals\Shared\Domain\Account\AccountAccessAuthCollectionInterface;
use Exdrals\Shared\Domain\Account\AccountAccessAuthInterface;
use Exdrals\Shared\Utils\Collection;
use InvalidArgumentException;

use function get_class;
use function sprintf;

class AccountAccessAuthCollection extends Collection implements AccountAccessAuthCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof AccountAccessAuthInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_class($value),
                    AccountAccessAuthInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
