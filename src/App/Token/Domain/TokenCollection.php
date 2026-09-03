<?php declare(strict_types=1);

namespace App\Token\Domain;

use Core\SharedKernel\Utils\Collection;
use InvalidArgumentException;

use function get_debug_type;
use function sprintf;

final class TokenCollection extends Collection implements TokenCollectionInterface
{
    /**
     * @throws InvalidArgumentException
     */
    #[\Override]
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
