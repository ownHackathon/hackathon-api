<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Domain;

use InvalidArgumentException;
use ownHackathon\Core\SharedKernel\Utils\Collection;

use function get_debug_type;
use function sprintf;

final class TokenCollection extends Collection implements TokenCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!($value instanceof TokenInterface)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    get_debug_type($value),
                    TokenInterface::class,
                ),
            );
        }
        parent::offsetSet($offset, $value);
    }
}
