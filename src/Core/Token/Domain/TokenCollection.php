<?php declare(strict_types=1);

namespace Exdrals\Core\Token\Domain;

use Exdrals\Core\Shared\Domain\Token\TokenCollectionInterface;
use Exdrals\Core\Shared\Domain\Token\TokenInterface;
use Exdrals\Core\Shared\Utils\Collection;
use InvalidArgumentException;

use function get_class;
use function sprintf;

class TokenCollection extends Collection implements TokenCollectionInterface
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
                    get_class($value),
                    TokenInterface::class
                )
            );
        }
        parent::offsetSet($offset, $value);
    }
}
